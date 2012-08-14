function saveTree(el) {
 parameters = {node_orders:treeObj.getNodeOrders(), transfered: TransferedNodes, method: 'post'};
 var url = window.location.toString();
 ajaxRequest(el, url, parameters, onSaveTree);
 ajaxRequest(el, url, parameters, function(el, result) {
	 jQuery.messaging.show( jQuery.parseJSON(result) );
	
 });
}
function onSaveTree(el, response) {
 TransferedNodes = response;
 $('save_button').disabled = false;
}

function copyLessonEntity(el, entity) {
 parameters = {entity:entity, method: 'get'};
 var url = window.location.toString();
 ajaxRequest(el, url, parameters, onCopyLessonEntity);
}
function onCopyLessonEntity(el, response) {
 TransferedNodes = response;
}
