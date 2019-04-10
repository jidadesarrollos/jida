<?php
/**
 * Clase Para consultas a la API de Instagram
 * @author JidaDesarrollos
 * @version v0.1
 * @category API
 * https://www.instagram.com/developer/
 */

namespace Jida\Componentes;

use App\Config\Configuracion;
use Jida\Medios\Debug;
use Jida\Core\Curl;

//include_once 'Framework/Core/Curl.php';

class InstagramManager {

    private $accessToken;
    private $secretID;

    private $version = 'v1';
    private $urlApi  = 'https://api.instagram.com/';
    /**
     * URL de Autorización para Instagram
     * @example https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=code&scope=SCOPE
     *
     */
    private $urlAuth = 'https://api.instagram.com/oauth/authorize/';
    /**
     * URL de Redireccion necesaria para que Instagram acceda a ella y solicite los permisos
     * Definida en el panel de control de la aplicacion
     */
    private $redirectURI = '';

    private $scopes = 'scope=public_content';

    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct () {

        $this->accessToken = Configuracion::INSTAGRAM_ACCESS_TOKEN;
        $this->secretID = Configuracion::INSTAGRAM_CLIENT_SECRET;
        $this->redirectURI = Configuracion::URL_REDIRECCION;
        $this->urlApi = $this->urlApi . $this->version . '/';
        $this->urlAuth = $this->urlAuth . '?client_id=' . $this->accessToken . '&redirect_uri=' . $this->redirectURI . '&response_type=code&' . $this->scopes;
    }

    /**
     * Envia a la URL de autenticación de instagram
     */
    function autenticar () {

        header('location:' . $this->urlAuth . '');
        exit;
    }

    function buscarID ($user, $token) {

        $url = $this->urlApi . 'users/search?q=' . $user . '&access_token=' . $token;
        $curl = new Curl($this->urlApi);
        $data = $curl->get([], $url)->arreglo();

        return $data['data'];
    }

    function solicitarAccessToken ($token) {

        $url = 'https://api.instagram.com/oauth/access_token';

        $params = [
            'client_id'     => $this->accessToken,
            'client_secret' => $this->secretID,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirectURI,
            'code'          => $token
        ];

        $curl = new Curl($this->urlApi);
        $data = $curl->post($params, $url)->arreglo();

        return $data;
    }

    function miGaleria ($accessToken) {

        $photo_count = 6;

        $url = $this->urlApi . 'users/self/media/recent/?access_token=' . $accessToken . '&COUNT=' . $photo_count;

        $curl = new Curl($this->urlApi);

        $data = $curl->get([], $url)->arreglo();

        return $data['data'];
    }

    /**
     * Obtiene las fotos de un usuario especificado por su user-id de Instagram
     * @example https://api.instagram.com/v1/users/{user-id}/media/recent/?access_token=ACCESS-TOKEN
     */
    function obtFotos ($accessToken, $userID) {

        $photo_count = 60;

        $url = $this->urlApi . 'users/' . $userID . '/media/recent/?access_token=' . $accessToken;

        $curl = new Curl($this->urlApi);

        $data = $curl->get([], $url)->arreglo();

        return $data['data'];
    }

    /**
     * Obtiene la lista de seguidores del usuario
     *
     * @method obtSeguidores
     * @param string $accessToken Token de API Instagram
     * @param int|string $userID ID de usuario Instagram
     * @return mixed
     */
    public function obtSeguidores ($accessToken, $userID) {

        $url = $this->urlApi . 'users/' . $userID . '/followed-by?access_token=' . $accessToken;

        $curl = new Curl($this->urlApi);

        $data = $curl->get([], $url)->arreglo();

        return $data;

    }

    /**
     * Obtiene la lista de seguidos por un usuario
     *
     * @method obtSeguidores
     * @param string $accessToken Token de API Instagram
     * @param int|string $userID ID de usuario Instagram
     * @return mixed
     */
    public function obtSeguidos ($accessToken, $userID) {

        $url = $this->urlApi . 'users/' . $userID . '/follows?access_token=' . $accessToken;

        $curl = new Curl($this->urlApi);

        $data = $curl->get([], $url)->arreglo();

        return $data;

    }

    /**
     * Obtiene los likes de un post
     *
     * @method obtLikes
     * @param string $accessToken Token de API Instagram
     * @param int|string $postID ID de post de Instagram
     *
     * @return mixed
     */
    public function obtLikes ($accessToken, $postID) {

        $url = $this->urlApi . 'media/' . $postID . '/likes?access_token=' . $accessToken;

        $curl = new Curl($this->urlApi);

        $data = $curl->get([], $url)->arreglo();

        return $data;

    }

    /**
     * Obtiene los comentarios de un post
     *
     * @method obtComentarios
     * @param string $accessToken Token de API Instagram
     * @param int|string $postID ID de post de Instagram
     *
     * @return mixed
     */
    public function obtComentarios ($accessToken, $postID) {

        $url = $this->urlApi . 'media/' . $postID . '/comments?access_token=' . $accessToken;

        $curl = new Curl($this->urlApi);

        $data = $curl->get([], $url)->arreglo();

        return $data;

    }

}
