{capture name="t_xcontent_schedule"}

<!-- 
<h4>Local de Prova</h4>
<p>Polo {$T_XCONTENT_USERPOLO.nome}</p>
<p>{$T_XCONTENT_USERPOLO.endereco}, {$T_XCONTENT_USERPOLO.numero} {$T_XCONTENT_USERPOLO.complemento}</p>
<p>{$T_XCONTENT_USERPOLO.bairro} - {$T_XCONTENT_USERPOLO.cidade}/{$T_XCONTENT_USERPOLO.uf}</p>
<p>Contato: {$T_XCONTENT_USERPOLO.telefone}</p>
 -->
<h4>Orientações para realização das provas presenciais:</h4>
<p>Ao aluno compete:</p>
<p>1. Agendar antecipadamente o seu dia e horário para a realização da  prova, observando o calendário disponível do seu polo.</p>
<p>2. Conferir o endereço ,telefone e meios de transporte até o  local evitando atrasos.</p>
<p>3. Chegar 15 min antes do início de cada prova.</p>
<p>4. Ter em mãos sua identidade ou qualquer outro documento com foto, bem como sua senha e login para acessar o sistema. Caso o aluno não tenha sua senha e login, ligar para a ULTCuritiba (41) 3016-1212 ou no skipe atendimento.ult com Marcia ou Adriane.</p>
<p>5. Durante a prova, seguir as orientações do Coordenador de Polo para a realização da mesma.</p>
<p>6. Assinar a lista de presença no Polo de Apoio Presencial.</p>

{/capture}

{sC_template_printBlock
	title 			= $smarty.const.__XCONTENT_WAITING_LIBERATION
	data			= $smarty.capture.t_xcontent_schedule
	contentclass	= "blockContents"
	class			= ""
}
