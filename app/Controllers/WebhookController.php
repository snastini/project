<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Services;

class WebhookController extends BaseController
{
    public function hitWebhook()
    {
        $clientUrl = Services::curlrequest([
            'base_uri'=> 'https://webhook.site',
            'http_errors'=> false,
        ]);

        $response = $clientUrl->get('241dfe35-60f3-4c37-9ccc-fed0ee67e71a');

        if ($response->getStatusCode() == 200) {
            return $this->response->setJSON([
                'message'=> 'success',
                'data'=> 'Webhook hit successfully'
            ]);
        }

        return $this->response->setJSON([
            'message'=> 'failes',
            'data'=> 'Webhook failed to hit'
        ]);

    }
}
