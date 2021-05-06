/* $(function(){
	read()
}); */
/* ----- READ --------*/
function read() {
	$("#loading").html('<img src="loading.gif">')
	var page = 1;
	var perpage = 10;
 	$.ajax({
	type: 'post',
	url: 'read.php',
	data: {
		from: $("#from").val(),
		until: $("#until").val(),
		codes: $("#codes").val(),
		teams: $("#teams").val(),
		bundle: $("#bundle").val(),
		searchstr: $("#searchstr").val(),
		fileName: $("#fileName").val(),
		page: page,
		perpage: perpage
	}
	}).done(function(data){
		$("#loading").empty();		
		$("#content").html(data)
	}).fail(function(data){
		$("#loading").empty()		
		alert('Failed.')
	}) 
};

function go() {
	var page = 1;
	var perpage = 10;
	$("#loading").html('<img src="loading.gif">')
 	$.ajax({
	type: 'post',
	url: 'read.php',
	data: {
		from: $("#from").val(),
		until: $("#until").val(),
		codes: $("#codes").val(),
		teams: $("#teams").val(),
		bundle: $("#bundle").val(),
		searchstr: $("#searchstr").val(),
		fileName: $("#fileName").val(),
		page: page,
		perpage: perpage
	}
	}).done(function(data){
		$("#loading").empty();		
		$("#content").html(data)
	}).fail(function(data){
		$("#loading").empty()		
		alert('Failed.')
	}) 
};

function load ( page, perpage, from, until, teams, codes, bundle, searchstr, fileName ) {
	$("#loading").html('<img src="../ExternalPersWakuBundle/loading.gif">');
	$.ajax({
		type: 'post',
		url: 'read.php',
		data: {
		from: $("#from").val(),
		until: $("#until").val(),
		codes: $("#codes").val(),
		teams: $("#teams").val(),
		bundle: $("#bundle").val(),
		searchstr: $("#searchstr").val(),
		fileName: $("#fileName").val(),
		page: 1,
		perpage: 10
		}
	}).done(function(data){
		$("#loading").empty();
		$("#content").html(data)		
	}).fail(function(data){
		alert('Failed.')
	})
}

$(document).on('click','.page',function(){
	var page = $(this).text();
	var perpage = 10;
	$("#loading").html('<img src="loading.gif">')
 	$.ajax({
	type: 'post',
	url: 'read.php',
	data: {
		from: $("#from").val(),
		until: $("#until").val(),
		codes: $("#codes").val(),
		teams: $("#teams").val(),
		bundle: $("#bundle").val(),
		searchstr: $("#searchstr").val(),
		fileName: $("#fileName").val(),
		page: page,
		perpage: perpage
	}
	}).done(function(data){
		$("#loading").empty();		
		$("#content").html(data)
	}).fail(function(data){
		$("#loading").empty()		
		alert('Failed.')
	}) 
});

load( 1, 10, $("#from").val(), $("#until").val(), $("#teams").val(), $("#codes").val(), $("#searchstr").val(), $("#bundle").val(), $("#fileName").val()  );



$('#searchstr').keyup(function(){
	var searchstr = document.getElementById('searchstr').value;
	searchTable(searchstr);
});

	
function searchTable(inputVal)
	{
	var table = $('#tblData1');
	table.find('tr').each(function(index, row)
	{
		var allCells = $(row).find('td');
			if(allCells.length > 0)
			{
			var found = false;
			allCells.each(function(index, td)
			{
			var regExp = new RegExp(inputVal, 'i');
			if(regExp.test($(td).text()))
			{
			found = true;
			$(row).fadeIn(500);
			return false;
			}
			});
			if(found == true)$(row).show();else $(row).hide();
			}
		});
	};
	
