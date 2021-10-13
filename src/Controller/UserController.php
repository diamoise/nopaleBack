<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private $nopale;
    private $serializer;
    private $em;
    private $repoUser;
    private $validator;
    private $encoder;

    public function __construct(
        SerializerInterface $serializer,
        ProfilRepository $pro,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        UserRepository $repo,
        Security $nopale,
        UserPasswordEncoderInterface $encoder

    )
    {
        $this -> serializer = $serializer;
        $this -> pro = $pro;
        $this -> repoUser = $repo;
        $this -> validator = $validator;
        $this -> em = $em;
        $this -> nopale = $nopale;
        $this -> encoder = $encoder;
    }
    /**
     * @Route("/api/nopale/users", name="add_user", methods="post"),
     */
    public function index(Request $request) {
        $user = $request -> request -> all();
        $use = new User();
        $photo = $request -> files -> get('photo');
        $profil = $this -> pro -> findOneByLibelle($user['profils']);
        $use -> setProfil($profil)
            -> setEmail($user['email'])
            -> setPrenom( $user['prenom'] )
            -> setNom( $user['nom'] )
            -> setTelephone( $user['telephone'] )
            -> setUsername( $user['prenom'].$user['nom'] )
            -> setAdresse($user['adresse'])
            -> setRoles(['ROLE_'.$profil -> getLibelle()]);
        // TODO set Photo
        if (!$photo) {
            return new JsonResponse('veuillez mettre une image', Response::HTTP_BAD_REQUEST, [], true);
        }
        $photoBlob = fopen($photo -> getRealPath(), 'rb');
        $use -> setPhoto($photoBlob);
        // TODO validations User
        $errors = $this -> validator -> validate($user);
        if (count($errors)) {
            $errors = $this -> serializer -> serialize($errors, 'json');
            return new JsonResponse($errors, Response:: HTTP_BAD_REQUEST, [], true);
        }
        // TODO setPassword
        $password = 'Nop@LEweDev221++';
        $use -> setPassword($this -> encoder -> encodePassword($use, $password));
        if ($this -> encoder -> encodePassword($use, $password)) {
            $this -> em -> persist($use);
            $this -> em -> flush();
            return new JsonResponse('User added to success', Response:: HTTP_CREATED);
        } else {
            return new JsonResponse('Password not work', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @Route("/api/nopale/user/{id}", name="edit_user", methods="put")
     */
    public function editUser(int $id, Request $request) {
        $user = $this -> repoUser -> find($id);
        $up = $request -> getContent();
        $cut = preg_split("/form-data; /", $up);
        unset($cut[0]);
        $data =[];
        foreach ($cut as $item) {
            $cool = preg_split("/\r\n/", $item);
            array_pop($cool);
            array_pop($cool);
            $find = explode('"', $cool[0]);
            $data[$find[1]] = end($cool);
        }
        if (isset($data["photo"])) {
            $stream = fopen('php://memory', 'r+');
            fwrite($stream, $data['photo']);
            rewind($stream);
            $data['photo'] = $stream;
        }

        foreach ($data as $item => $value) {
            if ($item !== "profils") {
                $setProperty = 'set'.ucfirst($item);
                $user -> $setProperty($value);
            }
        }
        $this -> em -> flush();
        return $this -> json("Utilisateur modifié avec success", Response::HTTP_OK);


    }
}
