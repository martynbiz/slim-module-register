<?php
namespace MartynBiz\Slim\Module\Register\Controller;

use MartynBiz\Slim\Module\Auth\Model\User;
use MartynBiz\Slim\Module\Register\RegisterValidator;
use MartynBiz\Slim\Module\Core\Controller as CoreController;

class RegisterController extends CoreController
{
    public function register($request, $response, $args)
    {
        // if errors found from post, this will contain data
        $params = $request->getParams();

        return $this->render('martynbiz-register::users/register', [
            'params' => $params,
        ]);
    }

    public function post($request, $response, $args)
    {
        $params = $request->getParams();
        $container = $this->getContainer();
        $settings = $container->get('settings')['martynbiz-register'];

        // validate form data

        // our simple custom validator for the form
        $validator = new RegisterValidator( $container['martynbiz-auth.model.user'] );
        $validator->setData($params);
        $i18n = $container->get('i18n');

        // first_name
        $validator->check('first_name')
            ->isNotEmpty( $i18n->translate('first_name_missing') );

        // last_name
        $validator->check('last_name')
            ->isNotEmpty( $i18n->translate('last_name_missing') );

        // email
        $validator->check('email')
            ->isNotEmpty( $i18n->translate('email_missing') )
            ->isEmail( $i18n->translate('email_invalid') )
            ->isUniqueEmail( $i18n->translate('email_not_unique'), $container['martynbiz-auth.model.user'] );

        // password
        $message = $i18n->translate('password_must_contain');
        $validator->check('password')
            ->isNotEmpty($message)
            ->hasLowerCase($message)
            ->hasNumber($message)
            ->isMinimumLength($message, 8);

        // agreement
        $validator->check('agreement');

        // more_info
        // more info is a invisible field (not type=hidden, use css)
        // that humans won't see however, when bots turn up they
        // don't know that and fill it in. so, if it's filled in,
        // we know this is a bot
        if ($validator->has('more_info')) {
            $validator->check('more_info')
                ->isEmpty( $i18n->translate('email_not_unique') ); // misleading msg ;)
        }

        // if valid, create user

        if ($validator->isValid()) {

            if ($user = $container['martynbiz-auth.model.user']->create($params)) {

                // set meta entries (if given)
                if (isset($params['source'])) $user->setMeta('source', $params['source']);

                // set session attributes w/ backend (method of signin)
                $container->get('martynbiz-auth.auth')->setAttributes( $user->toArray() );

                // // send welcome email
                // $container->get('mail_manager')->sendWelcomeEmail($user);

                // redirect
                return $response->withRedirect( $container->get('router')->pathFor( $settings['redirect_after_register']) );

            } else {
                $errors = $user->errors();
            }

        } else {
            $errors = $validator->getErrors();
        }

        $container->get('flash')->addMessage('errors', $errors);
        return $this->register($request, $response, $args);
    }
}
