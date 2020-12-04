<?php

namespace App\Controller;

use App\Entity\CM;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Service\UserService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $UserService;
    private $manager;
    private $repository;
    private $iriToObject;
    private $validator;
    public function __construct( 
        UserService $UserService ,
        UserRepository $Repository , 
        EntityManagerInterface $manager,
        IriConverterInterface $iriToObject,
        ValidatorInterface $validator)
        {
        $this->manager= $manager;
        $this->UserService = $UserService;
        $this->Repository=$Repository;
        $this->iriToObject = $iriToObject;
        $this->validator = $validator;
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
        return $this->UserService->add_user("App\Entity\CM", $request);
    }
    /**
     * @Route(
     * name="add_apprenant",
     * path="api/admin/users/apprenants",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addApprenant",
     * "_api_resource_class"=Apprenant::class,
     * "api_collection_operation_name"="add_apprenant"
     * }
     * )
     */
    public function addApprenant(Request $request)
    {
        return $this->UserService->add_user("App\Entity\Apprenant", $request);
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
        return $this->UserService->add_user("App\Entity\Formateur", $request);
    }

    /**
     * @Route(
     * name="add_admin",
     * path="api/admin/users/admins",
     * methods={"POST"},
     * defaults={
     * "_controller"="app\Controller\UserController::addAdmin",
     * "_api_resource_class"=Admin::class,
     * "api_collection_operation_name"="add_admin"
     * }
     * )
     */
    public function addAdmin(Request $request){
        return $this->UserService->add_user("App\Entity\Admin", $request);
    }
   /**  
     * @Route(
     * name="edit_user",
     * path="api/admin/users/{id}",
     * methods={"PUT"},
     * defaults={
     * "_controller"="app\Controller\UserController::edit_user",
     * "_api_resource_class"=User::class,
     * "api_item_operation_name"="edit_user"
     * }
     * )
     */
    public function edit_user(Request $request, int $id){
        $user=$this->Repository->find($id);
        $data=$request->request->all();
        
        foreach($data as $key=>$d){
            
            if($key != "_method" || !$d){

                if($key==='profil')
                {
                    $user->setProfil($this->iriToObject->getItemFromIri($d));
                    $user->settype($this->iriToObject->getItemFromIri($d)->getlibelle());
                    
                }else
                {
                    $user->{"set".ucfirst($key)}($d);
                }
            }
        }

        //$user->settype($this->Profil->getlibelle());
        //dd($user);
        
      // dd($user);
        // $errors=$this->validator->validate($user);
        // if(count($errors)){
        //     $errors = $this->serializer->serialize($errors,'json');
        //     return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        // }
        // $avatar=$request->file->get("avatar");
        // $avatar = fopen($avatar->getRealPath(), "rb");
        // if($avatar){
        //     $user->setAvatar($avatar);
        // }
        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse("Utulisateur mis a jour avec success",Response::HTTP_CREATED,[],true);
    }


    

}
