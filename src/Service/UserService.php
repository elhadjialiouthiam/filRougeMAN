<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $serializer;
    private $denormalizer;
    private $validator;
    private $manager;
    private $repository;
    public function __construct(UserPasswordEncoderInterface $encoder  ,UserRepository $userRepository ,DenormalizerInterface $denormalizer, SerializerInterface $serializer,  EntityManagerInterface $manager, ValidatorInterface $validator )
    {
        $this->encoder = $encoder;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->denormalizer=$denormalizer;
        $this->UserRepository=$userRepository;
    }

    public function add_user( $entity,Request $request){
        //recuperation des donnees de la requette
        $user = $request->request->all();
        
        //recuperation du photo
        $avatar = $request->files->get("avatar");

        //on ouvre le fichier et on le lit en format binaire
        $avatar = fopen($avatar->getRealPath(), "rb");
       
        $user = $this->denormalizer->denormalize($user, $entity, true);
        //dd($user->getProfil());
        $errors = $this->validator->validate($user);
       // dd($errors);
        if($errors){
            $errors = $this->serializer->serialize($errors,'json');
           // dd($errors);
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $user->setProfil($user->getProfil());
        $user->setAvatar($avatar);
        $user->setPassword($this->encoder->encodePassword($user,"password"));
        $this->manager->persist($user);
        $this->manager->flush();
        fclose($avatar);
        return new JsonResponse("Utulisateur Crée avec success",Response::HTTP_CREATED,[],true);

    }

    
    
}