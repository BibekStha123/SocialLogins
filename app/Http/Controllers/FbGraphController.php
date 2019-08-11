<?php

namespace App\Http\Controllers;

use Facebook\Facebook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FbGraphController extends Controller
{
    private $api;

    public function __construct(Facebook $fb)
    {
        $this->middleware(function ($request, $next) use ($fb) {
            $fb->setDefaultAccessToken(Auth::user()->fb_access_token);
            $this->api = $fb;
            return $next($request);
        });
    }

    /* public function publishToProfile(Request $request)
    {
        // dd($this->api);
        $image_path = Storage::disk("public")->path("/image/abcd.jpg");
        
        // dd($image_path);
        try {
            $response = $this->api->post('/me/feed', [
                'message' => "hello",

                //'source' => $this->api->fileToUpload($image_path),
            ],Auth::user()->fb_access_token);
            $response->getGraphNode();
            dd($image_path);

            if ($response['id']) {
                // post created
                dd("created");
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    } */

    public function publishToPage(Request $request){

        $page_id = '110096377007753';
        $image_path = Storage::disk("public")->path("/image/flower.jpg");
    
        try {
            $post = $this->api->post('/' . $page_id . '/photos', array('message'=>'First Post', 'source' => $this->api->fileToUpload($image_path)), $this->getPageAccessToken($page_id));
    
            $post = $post->getGraphNode()->asArray();
    
            dd($post);
    
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function getPageAccessToken($page_id){
        try {
             $response = $this->api->get('/me/accounts', Auth::user()->fb_access_token);
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    
        try {
            $pages = $response->getGraphEdge()->asArray();
            foreach ($pages as $key) {
                if ($key['id'] == $page_id) {
                    return $key['access_token'];
                }
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
}
