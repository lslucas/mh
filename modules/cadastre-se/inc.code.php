<?php

	/**
	 *SAVE CODE
	 */
	if ($act=='insert') {

		$sql= "UPDATE ".TP."_user SET usr_code=? WHERE usr_id=?";
		if (!$qry=$conn->prepare($sql))
		 echo divAlert($conn->error);

		else {

			$code = newCode($val['cpf']);

			$qry->bind_param('si', $code, $res['item']);
			$qry->execute();
			$qry->close();

			saveCode($code);
		}

	}
