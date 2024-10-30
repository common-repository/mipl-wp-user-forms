<?php
/* MI CURL request
Version: 1.4.3

$atts = array(
    'data'        => array(), // GET/POST Data
    'method'      => 'GET',   // GET/POST Method
    'referer_url' => '',      // HTTP referer url
    'http_header' => array(), // Send Row HTTP header
    'header'      => false,   // Get HTTP header of responce
    'timeout'     => 0        // Connection and Request Time Out
);

Return array(
    'resp' => ' Responce value'
    'info' => array( 'Request and Responce details' )
);

Ex.
1) For ajax request: 
   $http_header = array('X-Requested-With: XMLHttpRequest');
2) Submit row file content with type
   $http_header = array('Content-Type: text/xml');
   $http_header = array('Content-Type: application/json');
3) To Send/Upload file: 
   $atts['data'] = array( 'file' = '@/filepath/filename.jpg' );
*/

function mipluf_curl_request( $url, $atts = array() ){
    
    $args = array(
        'data'        => array(),
        'method'      => 'GET',
        'referer_url' => '',
        'http_header' => array(),
        'header'      => false,
        'timeout'     => 5
    );
    
    $args = array_merge( $args, $atts );
    
    set_time_limit( $args['timeout'] );
    
    if (function_exists("curl_init") && $url) {
        
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, $user_agent );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_HEADER, $args['header'] );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $args['timeout'] );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $args['timeout']/4 );
		
        $req_method = strtolower($args['method']);

        if ( $req_method == 'post' ) {
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $args['data'] );
        } else if ( $req_method == 'put' ) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $args['data'] );
        } else if ( $req_method == 'delete' ) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $args['data'] );
        } else {
            curl_setopt( $ch, CURLOPT_HTTPGET, 1);
            $query_string = http_build_query($args['data']);
            if ( $query_string != '' ) {
                $url = trim( $url, "?" ) . "?" . $query_string;
            }
        }

        if ( $args['referer_url'] != '' ) { 
            curl_setopt( $ch, CURLOPT_REFERER, $args['referer_url'] );
        }

        if ( !empty( $args['http_header'] ) ) {
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $args['http_header'] );
        }

        curl_setopt( $ch, CURLOPT_URL, $url );
        
        $resp_body = curl_exec($ch);
        $info = curl_getinfo($ch);
        
        $response = array(
            'body' => $resp_body,
            'info' => $info
        );
        
        $response['http_code'] = $info['http_code'];
        
        if( $args['header'] ){
            $header_size = $info['header_size'];
            $body = substr($resp_body, $header_size);
            $header = substr($resp_body, 0, $header_size);
            $header_arr = array();
            $header_lines = explode("\n", $header);
            foreach($header_lines as $header_line){
                $header_item = explode(':',$header_line);
                if(count($header_item)>=2){
                    $key = trim($header_item[0]);
                    unset($header_item[0]);
                    $value = trim(implode(':',$header_item));
                    $header_arr[$key] = trim($value);
                }
            }
            $response['body'] = $body;
            $response['header'] = $header_arr;
        }
        
        return $response;
        
    }
    
}
