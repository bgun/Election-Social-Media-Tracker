// JavaScript Document

// global variables used throughout the script
var predictor = "";
var racetype = "";

$(document).ready(function(){
	// set default predictor and racetype
	changeRacetype("Senate");
	changePredictor("Twitter");
	updateStats();

	// set click events
	$('#header ul li a.Senate').click(function(){ changeRacetype("Senate"); updateStats(); return false; });
	$('#header ul li a.Governor').click(function(){ changeRacetype("Governor"); updateStats(); return false; });
	$('#change-predictor li a.Twitter').click(function(){  changePredictor("Twitter"); updateStats(); return false; });
	$('#change-predictor li a.Facebook').click(function(){ changePredictor("Facebook"); updateStats(); return false; });	
});

var changeRacetype = function(r){
	// set global racetype;
	racetype = r;

	// update button class
	$('#header ul li a').removeClass('active');	
	$('#header ul li a.'+r).addClass('active');
	
	// show races
	$('div.race').hide();
	$('div.race-'+r).show();
}

var changePredictor = function(p){
	// set global predictor status
	predictor = p;
		
	// update button class
	$('#change-predictor li a').removeClass('active');
	$('#change-predictor li a.'+p).addClass('active');

	// change sort order in race tables
	if(predictor == "Twitter") sortColumn = 1;
	if(predictor == "Facebook") sortColumn = 2;
	$('div.race table').tablesorter({
		sortList: [[sortColumn,1]]
	});

	// show races
	$('div.race').hide();
	$('div.race-'+racetype).show();
}

var updateStats = function(){
	var totalRaces = 0;
	var sortColumn = 0;
	var wonD = 0, wonR = 0, wonI = 0;
	var racetext = racetype;
	var correctTimes = 0;
	var correctPercent = 0;

	totalRaces = $('div.race:visible').length;
	sortColumn = 0;

	if(racetext == "Governor") { racetext = "Gubernatorial" }
	$('#racetype').text(racetext);
	
	$('#stats .predictor').text(predictor);
	$('#stats .number-of-races').text(totalRaces+" "+racetext);

	$('div.race table tbody tr').removeClass('pick');
	$('div.race table tbody:visible').each(function(){
		if( $(this).find('tr:eq(0)').hasClass('affiliation-D') ) wonD += 1;
		if( $(this).find('tr:eq(0)').hasClass('affiliation-R') ) wonR += 1;
		if( $(this).find('tr:eq(0)').hasClass('affiliation-I') ) wonI += 1;

		$(this).find('tr:eq(0)').addClass('pick');
		if( $(this).find('tr:eq(0)').hasClass('winner-1') ) correctTimes += 1;
	});

	$('#stats .stats-won-D span').text(wonD);
	$('#stats .stats-won-R span').text(wonR);
	$('#stats .stats-won-I span').text(wonI);
	
	$('#stats .accuracy .times').text(correctTimes);
	correctPercent = correctTimes/totalRaces * 100;
	$('#stats .accuracy .percent').text(correctPercent.toFixed(1));
}


