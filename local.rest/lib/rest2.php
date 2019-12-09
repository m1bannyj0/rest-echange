<?php

namespace Local\Rest;

class Rest2
{
    public function dataGet(\Local\Rest\Request $request)
    {
        $userId = $request->getQuery('id');

        if(!$userId) {
            throw new \Local\Rest\RestException('No user_id passed'); 
        }

        $request->withStatus(200);
        $request->withHeader('X-Token', 'local-web-access');

        return ['user' => ['NAME' => 'LocalAnswerTest']];
    }

    public function dataUpdate(\Local\Rest\Request $request)
    {
        $userId = $request->getQuery('id');
        $fields = $request->getQuery('fields');

        if(!$userId) {
            throw new \Local\Rest\RestException('No user_id passed'); 
        }

        if(!$fields) {
            throw new \Local\Rest\RestException('No fields passed'); 
        }

        $request->withStatus(200);
        $request->withHeader('X-Token', 'prominado-web-access');

        return ['user' => ['NAME' => 'Prominado']];
    }

    public function isAuthorized(Request $request) 
    {
        $server = $request->getServer();
    
        preg_match('/Bearer\s(.*)/', $server['REMOTE_USER'], $matches);
        if ($matches[1]) {
            return true;
        }

        return false;
    }
}