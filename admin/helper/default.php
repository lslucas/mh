<?php
	/*
	 *cabeçalho
	 */
/*
	$rp = '../';
	include_once $rp.'_inc/global.php';
	include_once $rp.'_inc/db.php';
	include_once $rp.'_inc/global_function.php';
	include_once $rp.'inc.auth.php';
	$minhasnotas = $lstProfessor = $lstProfessorDisciplina = null;
	$adm_id = intval($_SESSION['user']['id']);
	$arrAluno = array();
	$listDisciplinas = getListDisciplinas();
	$lstTurmas = getListTurma();

	*
	 * variaveis do aluno
	 ***********************************************
	if ($_SESSION['user']['tipo']=='Aluno') {

		$sqla = "SELECT
						alu_id,
						alu_registro,
						(SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) nome,
						(SELECT adm_email FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) email,
						alu_sexo,
						alu_cpf,
						alu_rg,
						alu_telefone,
						alu_celular,
						alu_nome_financeiro,
						alu_email_financeiro,
						alu_telefone_financeiro,
						alu_celular_financeiro,
						(SELECT GROUP_CONCAT(rat_cat_id) FROM ".TABLE_PREFIX."_r_alu_turmas WHERE rat_adm_id=alu_adm_id)
					FROM ".TABLE_PREFIX."_aluno 
					WHERE alu_adm_id=?";

		if(!$qrya = $conn->prepare($sqla))
			echo divAlert($conn->error);

		else {

			$qrya->bind_param('i', $adm_id);
			$qrya->bind_result(
				$id,
				$registro,
				$nome,
				$email,
				$sexo,
				$cpf,
				$rg,
				$telefone,
				$celular,
				$nome_financeiro,
				$email_financeiro,
				$telefone_financeiro,
				$celular_financeiro,
				$turmas
			);
			$qrya->execute();
			$qrya->fetch();
			$qrya->close();


			*
			 *turmas que pertence esse aluno
			 *
			$turma = explode(',', $turmas);
			$liTurmas = null;
			$todasTurmas = null;
			foreach ($turma as $trm) {
				$turmaNome = isset($lstTurmas[$trm]) ? $lstTurmas[$trm] : '[indefinido]';
				$todasTurmas = isset($lstTurmas[$trm]) ? $lstTurmas[$trm].', ' : null;
				$liTurmas .= "<li><a href='?p=aluno&desempenho&item=$id&adm_id=$adm_id&turma=$trm'>Desempenho no $turmaNome</a></li>";
			}

			if (empty($liTurmas))
				$liTurmas .= "<li>Sem Turma</li>";



			$arrAluno = array(
				'id'=>$id,
				'nome'=>$nome,
				'email'=>$email,
				'registro'=>$registro,
				'sexo'=>$sexo,
				'cpf'=>$cpf,
				'rg'=>$rg,
				'telefone'=>$telefone,
				'celular'=>$celular,
				'nome_financeiro'=>$nome_financeiro,
				'email_financeiro'=>$email_financeiro,
				'telefone_financeiro'=>$telefone_financeiro,
				'celular_financeiro'=>$celular_financeiro,
				'turmas'=>$todasTurmas,
				'liTurmas'=>$liTurmas,
			);
		}
	}

	
	 * gera o html do minha nota (apenas para alunos)
	 ***********************************************
	$sqlr = "SELECT
					ran_disciplina_id,
					ran_professor_id,
					(SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=ran_professor_id) professor,
					ran_turma_id,
					ran_periodo_tipo,
					ran_periodo_num,
					ran_media,
					ran_falta
				FROM ".TABLE_PREFIX."_r_alu_notas 
				INNER JOIN ".TABLE_PREFIX."_categoria 
					ON ran_turma_id=cat_id
					AND cat_area='Turmas'
					AND cat_status=1

				WHERE ran_adm_id=?";

	$val = $lstProfessor = array();
	if(!$qryr = $conn->prepare($sqlr))
		echo divAlert($conn->error);

	elseif ($_SESSION['user']['tipo']=='Aluno') {

		$qryr->bind_param('i', $adm_id);
		$qryr->bind_result(
			$disciplina_id,
			$professor_id,
			$professor,
			$turma_id,
			$periodo_tipo,
			$periodo_num,
			$media,
			$falta
		);
		$qryr->execute();

		while($qryr->fetch()) {
			$lstProfessor[$professor_id] = $professor;
			$lstProfessorDisciplina[$professor_id] = $listDisciplinas[$disciplina_id];
			$val[$adm_id][$periodo_num][$disciplina_id]['media'] = $media;
			$val[$adm_id][$periodo_num][$disciplina_id]['falta'] = $falta;
		}

		$qryr->close();

$minhasnotas .= <<<html
			<table width='100%' class="table table-condensed">
			   <thead> 
				  <tr>
					<th width="150px" colspan=2 rowspan=2>Disciplinas</th>
					<th width="60px" colspan=2><center>1º Bimestre</center></th>
					<th width="60px" colspan=2><center>2º Bimestre</center></th>
					<th width="60px" colspan=2><center>3º Bimestre</center></th>
					<th width="60px" colspan=2><center>4º Bimestre</center></th>
					<th width="30px" align=center class='tip' title='Recuperação'>Recup.</th>
					<th width="60px" colspan=2><center>Final</center></th>
				  </tr>
				  <tr>
					<th width="30px">Média</th>
					<th width="30px">Faltas</th>
					<th width="30px">Média</th>
					<th width="30px">Faltas</th>
					<th width="30px">Média</th>
					<th width="30px">Faltas</th>
					<th width="30px">Média</th>
					<th width="30px">Faltas</th>
					<th width="30px">Média</th>
					<!--<th width="30px">Faltas</th>-->
					<th width="30px">Média</th>
					<th width="30px">Faltas</th>
				  </tr>
			   </thead>  
			   <tbody>
html;

				foreach($listDisciplinas as $did=>$dnome) {

					$classColor = $labelStatus = $media = null;
					if (isset($val[$adm_id][6][$did]['media'])) {
						if (isset($val[$adm_id][1][$did]['media']) && isset($val[$adm_id][2][$did]['media'])) {
							$media = $val[$adm_id][6][$did]['media'];

							if ($media>=7) {
								$classColor = 'info';
								$labelStatus = "\n<br/><span class='label label-{$classColor}'>Aprovado</span>";
							} elseif($media>=4 && !isset($val[$adm_id][5][$did]['media'])) {
								$classColor = 'warning';
								$labelStatus = "\n<br/><span class='label label-{$classColor}'>Recuperação</span>";
							} else {
								$classColor = 'important';
								$labelStatus = "\n<br/><span class='label label-{$classColor}'>Reprovado</span>";
							}
						}
					}


					$minhasnotas.="\n\t<tr>";
					$minhasnotas.="\n\t\t<td colspan=2>{$dnome}{$labelStatus}</td>";

					for($i=1; $i<=6; $i++) {

						$media  = isset($val[$adm_id][$i][$did]['media']) ? $val[$adm_id][$i][$did]['media'] : '--';
						$falta = isset($val[$adm_id][$i][$did]['falta']) ? $val[$adm_id][$i][$did]['falta'] : '--';
						$classColor = null;

						if (!is_null($media) && $media<>'--') {
							if ($media>=7)
								$classColor = ' info';
							elseif($media>=4 && !isset($val[$adm_id][5][$did]['media']))
								$classColor = ' warning';
							else
								$classColor = ' error';
						}

						$minhasnotas.="\n\t\t<td>";

						$minhasnotas.="<div class='control-group{$classColor}'>";
						$minhasnotas.="<input type='text' class='input-nano' disabled placeholder='--' value='{$media}'>";
						$minhasnotas.="</div>";
						$minhasnotas.="</td>";

						if ($i<>5) {
							$minhasnotas.="\n\t\t<td>";
							$minhasnotas.="<input type='text' class='input-nano' disabled placeholder='--' value='{$falta}'>";
							$minhasnotas.="</td>";
						}
					}

					$minhasnotas.="\n\t</tr>";
				}

		$minhasnotas.= "\n\t</tbody>";
		$minhasnotas.= "\n</table>";
	}
	*
	 * fim/gera o html do minha nota (apenas para alunos)
	 ***********************************************


	
	 * gera html para meus professores
	 ***********************************************
	$meusprofessores = null;

	
	 * gera o html do minha nota (apenas para alunos)
	 **********************************************
	$sqlprof = "SELECT
					adm_id,
					adm_nome,
					adm_email,
					cat_id disciplina_id,
					cat_titulo disciplina,
					prof_registro

				FROM ".TABLE_PREFIX."_administrador 

				INNER JOIN ".TABLE_PREFIX."_professor 
					ON prof_adm_id=adm_id

				INNER JOIN ".TABLE_PREFIX."_r_prof_disciplinas 
					ON adm_id=rpd_adm_id

				INNER JOIN ".TABLE_PREFIX."_categoria 
					ON rpd_cat_id=cat_id

				WHERE adm_status=1
					AND adm_tipo='Professor'";

	if(!$qryprof = $conn->prepare($sqlprof))
		echo divAlert($conn->error);

	//elseif ($_SESSION['user']['tipo']=='Aluno') {
	else {

		$qryprof->bind_result(
			$professor_id,
			$professor,
			$professor_email,
			$professor_disciplina_id,
			$professor_disciplina,
			$professor_registro
		);
		$qryprof->execute();


$meusprofessores .= <<<html
					<table width='100%' class="table table-condensed">
					   <thead> 
						  <tr>
							<th width="250px">Professor</th>
							<th width="200px">Email</th>
							<th>Disciplina</th>
						  </tr>
					   </thead>  
					   <tbody>
html;


		$lstProfessor = array();
		while($qryprof->fetch()) {
			$lstProfessor[$professor_id]['nome'] = $professor;
			$lstProfessor[$professor_id]['email'] = $professor_email;
			$lstProfessor[$professor_id]['registro'] = $professor_registro;
			$lstProfessor[$professor_id]['disciplina'][$professor_disciplina_id] = $professor_disciplina;
		}


		foreach ($lstProfessor as $prof_id=>$prof) {

			$prof['disciplina'] = join(', ', $prof['disciplina']);

			$meusprofessores .= "\n\t<tr>";
			$meusprofessores .= "\n\t\t<td>{$prof['nome']}</td>";
			$meusprofessores .= "\n\t\t<td>{$prof['email']}</td>";
			$meusprofessores .= "\n\t\t<td>{$prof['disciplina']}</td>";
			$meusprofessores .= "\n\t</tr>";

		}


		$qryprof->close();
		$meusprofessores .= "\n\t</tbody>";
		$meusprofessores .= "\n</table>";

	}
 */
