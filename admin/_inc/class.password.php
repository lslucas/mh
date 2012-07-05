<?php


class Password {

  /*
   Copyright 2011 Lucas Serafim

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
   */

   /*
   * @author: Lucas Serafim, lslucas@gmail.com
   * @site: www.lucasserafim.com.br
   * This class verify php version and suport for mcrypt
   * if not suport mcrypt use md5, if php version is lower 5.0.0 not use binary format
   *
   ####usage
   *$pass = new Password;
   *var_dump($pass->hash('mypassword'));
   *ou var_dump($pass->hash('mypassword', 'md5', 'my salt'));
   */


  private $_input, $_key, $_type, $encrypted_data;
  public $used;



  //clean vars
  public function __destruct() {
    unset($encrypted_data, $_input, $_type, $_key);
  }



  /*
   *generate the password with parameters
   @params: password, function to use: mcrypt (default) or md5, key or your salt (I use server_name with default)
   @return string
   */
  public function hash($_input, $_type='mcrypt', $_key='your salt') {


      /*
       *if exists mcrypt and $_type is mcrypt
       */
      if( function_exists('mcrypt') && $_type=='mcrypt' ) {

          $td = mcrypt_module_open(MCRYPT_TWOFISH256, '', 'ofb', '');
          $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_BLOWFISH);

          mcrypt_generic_init($td, $_key, $iv);
          $encrypted_data = mcrypt_generic($td, $_input);
          mcrypt_generic_deinit($td);
          mcrypt_module_close($td);

          $this->used = 'mcrypt';

      //else use md5
      } else {

        if(version_compare(PHP_VERSION, '5.0.0', '>='))
          $bool = true;
        else $bool = false;

          $this->used = $bool ? 'md5 with PHP5+' : 'md5 with PHP5-';
          $encrypted_key  = md5($_key, $bool).md5($_input, $bool);
          $encrypted_data = md5($encrypted_key, $bool);

      }


    // return generated password
    // enjoy
    return md5(utf8_encode($encrypted_data));


  }



}
