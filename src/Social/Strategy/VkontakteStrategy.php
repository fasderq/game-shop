<?php
namespace GameShop\Site\Social\Strategy;


use GameShop\Site\Social\Exceptions\AccessDenied;
use GameShop\Site\Social\Exceptions\CorruptedResponse;
use GameShop\Site\Social\Interfaces\StrategyInterface;
use GameShop\Site\Social\Model\Profile;
use GameShop\Site\Social\Model\Session;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VkontakteStrategy implements StrategyInterface
{
    protected $httpClient;
    protected $clientId;
    protected $clientSecret;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->clientId = $httpClient->getConfig('client_id');
        $this->clientSecret = $httpClient->getConfig('client_secret');
    }

    /**
     * @param SessionInterface $httpSession
     * @param string $redirectUrl
     * @return string
     */
    public function getOAuthUrl(SessionInterface $httpSession, string $redirectUrl): string
    {
        $httpSession->set('social.vk.redirect_uri', $redirectUrl);
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUrl,
            'display' => 'page',
            'scope' => 'email',
            'response_type' => 'code',
            'v' => '5.67',
            'state' => ''
        ];
        return 'https://oauth.vk.com/authorize?' . urldecode(http_build_query($params));
    }

    /**
     * @param Request $request
     * @return Session
     * @throws AccessDenied
     * @throws CorruptedResponse
     */
    public function instantiateSession(Request $request): Session
    {
        if ('access_denied' === $request->get('error')) {
            throw new AccessDenied('access denied from vk');
        }
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $request->getSession()->get('social.vk.redirect_uri'),
            'response_type' => 'token',
            'code' => $request->get('code'),
            'scope' => 'phone'
        ];

        try {
            $res = $this->httpClient->request(
                'GET',
                'https://oauth.vk.com/access_token?' . urldecode(http_build_query($params))
            );
            $tokenInfo = json_decode($res->getBody()->getContents());
        } catch (\Exception $e) {
            throw new CorruptedResponse('internal server error from vk');
        }

        if (empty($tokenInfo->access_token)) {
            throw new CorruptedResponse('internal server error from vk t');
        }

        return new Session(
            $tokenInfo->access_token,
            (new \DateTime())->setTimestamp(time() + $tokenInfo->expires_in)
        );
    }

    /**
     * @param Session $session
     * @return Profile
     */
    public function getProfile(Session $session): Profile
    {
        $params = [
            'city',
            'sex',
            'bdate',
            'contacts',
        ];
        $fields = urldecode(http_build_query([
            'fields' => implode(',', $params),
            'access_token' => $session->getAccessToken()
        ]));
        $url = 'https://api.vk.com/method/users.get?' . $fields;
        $res = $this->httpClient->request('GET', $url);
        $userInfo = json_decode($res->getBody()->getContents())->response[0];

        return new Profile(
            $userInfo->uid,
            null,
            $userInfo->first_name,
            $userInfo->last_name,
            $userInfo->bdate ? \DateTime::createFromFormat('d.m.Y', $userInfo->bdate) : null,
            null,
            null,
            null,
            null,
//            $this->normalizeSex($userInfo->sex),
            $userInfo->home_phone,
            $userInfo->email ?? []
        );
    }
//    /**
//     * @param int|null $sex
//     * @throws \InvalidArgumentException
//     * @return null|string
//     */
//    private function normalizeSex(? int $sex): ?string
//    {
//        switch ($sex) {
//            case 0:
//                return null;
//            case 1:
//                return SexEnum::FEMALE;
//            case 2:
//                return SexEnum::MALE;
//            default:
//                throw new \InvalidArgumentException(sprintf('Unknown sex %s', $sex));
//        }
//    }
}
