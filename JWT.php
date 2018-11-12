<?php

/**
 * JWT Implementation Using CorePHP and JQuery
 * @author Anil Kumar (https://github.com/anil-ajax)
 * This code follow PSR standards (https://github.com/anil-ajax/psr-standards)
 */

$token_id    = base64_encode(mcrypt_create_iv(32)); // generate random but unique id
$issued_at   = time();
$not_before  = $issued_at + 10;            
$expire      = $not_before + 3600;  	

$data = [
    'iat'  => $issued_at,         	
    'jti'  => $token_id,          	
    
    'nbf'  => $not_before,        	
    'exp'  => $expire,           	
    'data' => [                  	
        'userId'   => 1, 			
        'userName' => 'anil_kumar', 
    ]
];

// change array to JWT
$key = 'somereandomkey'; // random key define in your config
$secret_key = base64_decode($key);

// make sure php-jwt is installed
$jwt = JWT::encode(
    $data,      
    $secret_key, 
    'HS512'    
    );
    
$unencoded_arr = ['jwt' => $jwt];
// echo json_encode($unencoded_arr); // just for testing
?>

<script>
// send request
// following code used JQuery so make sure you include JQuery in your code
$(function(){
    var store = store || {};
    
    store.setJWT = function(data){
        this.JWT = data;
    }
    
    /*
     * Submit the login form via ajax
     */
	$("#login_btn").submit(function(e){
	      
	        $.post('auth/token', $("#login_frm").serialize(), function(data){
	            store.setJWT(data.JWT);
	        }).fail(function(){
	            // handle error
	        });
	    });
});

// get response
$("#get_resp_btn").click(function(e){
        
        $.ajax({
            url: 'resource/image',
            beforeSend: function(request){
                request.setRequestHeader('Authorization', 'Bearer ' + store.JWT);
            },
            type: 'GET',
            success: function(data) {
                // business logic
            },
            error: function() {
                // handle error
            }
        });
    });

</script>
