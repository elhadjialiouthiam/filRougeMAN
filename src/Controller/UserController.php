<?php

namespace App\Controller;

use App\Entity\CM;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    public function __construct(UserPasswordEncoderInterface $encoder , SerializerInterface $serializer,  EntityManagerInterface $manager, ValidatorInterface $validator )
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
    }

    public function add_user( $entity,Request $request){
        //recuperation des donnees de la requette
        $user = $request->request->all();
        //recuperation du photo
        $avatar = $request->files->get("avatar");

        //on ouvre le fichier et on le lit en format binaire
        $avatar = fopen($avatar->getRealPath(), "rb");
       
        $user = $this->serializer->denormalize($user, $entity, true);
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
        fclose($avatar);
        return new JsonResponse("Utulisateur Créé avec success",Response::HTTP_CREATED,[],true);

    }
       /**  
     * @Route(
     * name="add_cm",
     * path="api/admin/users/cms",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addCM",
     * "_api_resource_class"=CM::class,
     * "api_collection_operation_name"="add_cm"
     * }
     * )
     */
    public function addCM(Request $request){
        return $this->add_user("App\Entity\CM", $request);
    }
/**
     * @Route(
     * name="add_apprenant",
     * path="api/admin/users/apprenants",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addApprenant",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="add_apprenant"
     * }
     * )
     */
    public function addApprenant(Request $request)
    {
        return $this->add_user("App\Entity\Apprenant", $request);
    }
       /**
     * @Route(
     * name="add_formateur",
     * path="api/admin/users/formateurs",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addFormateur",
     * "_api_resource_class"=Formateur::class,
     * "api_collection_operation_name"="add_formateur"
     * }
     * )
     */
    public function addFormateur(Request $request)
    {
        return $this->add_user("App\Entity\Formateur", $request);
    }

    /**
     * @Route(
     * name="add_admin",
     * path="api/admin/users/admins",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addAdmin",
     * "_api_resource_class"=User::class,
     * "api_collection_operation_name"="add_admin"
     * }
     * )
     */
    public function addAdmin(Request $request){
        return $this->add_user("App\Entity\Admin", $request);
    }

}
