<?php

if( ! defined("WEBDB_EXEC") ) die("No direct access!");


class Models_Users extends Models_Base {
    const TABLENAME = "users";
    public $id;
    public $nick;
    public $pass;
    public $emails;
    public $ts_registered;
    public $role;
    public $firstname;
    public $lastname;
    


    public function declareFields() {
        $fields = array(
            "id",
            "nick",
            "pass",
            "email",
            "ts_registered",
            "role",
            "fistname",
            "lastname"
        );
        return $fields;
    }
    
    


	public function getReplies() {
$query = Models_Reply::getSelect() . " WHERE thread_id = `" . $this->id . "`";
return Models_Reply::fetchByQuery($query);
}


public static function getCredit($callingUser, $checkReply, $changeAsked) {
$oldCredit = $checkReply->getYourCredit($callingUser);
if ( ! $oldCredit) {

			return true;

} else {

		return ($oldCredit->value != $changeAsked) ? $oldCredit : false;
}
}

}




?>
