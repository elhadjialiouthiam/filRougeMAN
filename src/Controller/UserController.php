<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController 
{
    private $encoder;
    private $serializer;
    private $validator;
    private $manager;
    public function __construct(UserPasswordEncoderInterface $encoder , SerializerInterface $serializer,  EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')", message="Acces non autorisé") 
     * @Route(
     * name="add_user",
     * path="api/admin/users",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::add",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="add_user"
     * }
     * )
     */
    public function add_user(Request $request)
    {
        //recuperation des données de la requette
        $user = $request->request->all();
        //recuperation du photo
        $avatar = $request->files->get("avatar");

        //on ouvre le fichier et on le lit en format binaire
        $avatar = fopen($avatar->getRealPath(), "rb");
       
        $user = $this->serializer->denormalize($user, "App\Entity\User", true);
        //dd($user->getProfil());
        $errors = $this->validator->validate($user);
        if(count($errors) > 0){
            $errors = $this->serializer->serialize($errors,'json');
           // dd($errors);
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $user->setProfil($user->getProfil());
        $user->setStatut("0");
        $user->setAvatar($avatar);
        $user->setPassword($this->encoder->encodePassword($user,"password"));
        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse("Utulisateur Créé avec success",Response::HTTP_CREATED,[],true);
    }

}
