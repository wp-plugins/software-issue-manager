jQuery(document).ready(function() {
$=jQuery;
var $captcha_container = $('.captcha-container');
if ($captcha_container.length > 0) {
        var $image = $('img', $captcha_container),
        $anchor = $('a', $captcha_container);
        $anchor.bind('click', function(e) {
                e.preventDefault();
                $image.attr('src', $image.attr('src').replace(/nocache=[0-9]+/, 'nocache=' + +new Date()));
        });
}
$.validator.setDefaults({
    ignore: [],
});
$.extend($.validator.messages,issue_entry_vars.validate_msg);
$('#emd_iss_due_date').datepicker({
'dateFormat' : 'mm-dd-yy'});
$.validator.addMethod('uniqueAttr',function(val,element){
  var unique = true;
  var data_input = $("form").serialize();
  $.ajax({
    type: 'GET',
    url: issue_entry_vars.ajax_url,
    cache: false,
    async: false,
    data: {action:'emd_check_unique',data_input:data_input, ptype:'emd_issue',myapp:'sim_com'},
    success: function(response)
    {
      unique = response;
    },
  });
  return unique;                
}, issue_entry_vars.unique_msg);
$('#issue_entry').validate({
onfocusout: false,
onkeyup: false,
onclick: false,
errorClass: 'text-danger',
rules: {
  'issue_priority':{
required:true,
},
'issue_status':{
required:true,
},
'issue_cat':{
required:true,
},
'issue_tag[]':{
required:false,
},
'browser[]':{
required:false,
},
'operating_system[]':{
required:false,
},
'rel_project_issues[]':{
required:true,
},
blt_title : {
required  : true,
},
blt_content : {
required  : false,
},
emd_iss_due_date : {
required  : false,
},
emd_iss_document : {
required  : false,
},
},
success: function(label) {
},
errorPlacement: function(error, element) {
if (typeof(element.parent().attr("class")) != "undefined" && element.parent().attr("class").search(/date|time/) != -1) {
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("radio") != -1){
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("select2-offscreen") != -1){
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("selectpicker") != -1 && element.parent().parent().attr("class").search("form-group") == -1){
error.insertAfter(element.parent().find('.bootstrap-select').parent());
} 
else if(element.parent().parent().attr("class").search("pure-g") != -1){
error.insertAfter(element);
}
else {
error.insertAfter(element.parent());
}
},
});
});
