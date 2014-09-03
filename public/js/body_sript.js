	var	questNr		= 0,    //counter for how many questions and for tableNum array
		timerHandle	= 0,
		timerleftPX	= -100,    //counter box img left px
		timerVal    = 10,
		lifeleftPX	= -100,    //counter for live box
		lifeVal     = 5,   //
		a1          = 0,    //time lock and disable clicks on start btn id == 1
		startTime 	= 0,    //timestamp
		pointsSum   = 0,    // gameLevel of game
		gameLevel   = 0,    // gameLevel of game
		tableNum    = [],   //result table number    
		myDataRes   = [],   //temporary array
		myDataUsr   = [],   //temporary array
		newGame     = false, //check for new game
		gameStarted = false, //check for new game
		timeSpend=0,
		num1=0,
		num2=0;
	$(document).ready(function(){
		getTopScore();

		$(document).on("click", "#start_button", function(){
				if(newGame == true){
					//start game again
					a1 = 0;
					timerleftPX	= -100,
					timerVal	= 10,
					lifeleftPX 	= -100,
					pointsSum 	= 0,
					lifeVal     = 5,
					gameLevel   = 0,
					questNr     = 0,
					tableNum    = [],
					newGame     = false,
					gameStarted = false,
					timeSpend=0,
					num1=0,
					num2=0;
					$('#gameResult_wrap each').remove();
						$('#time_counter .progress_bar').css('left', timerleftPX+'%' ); //colored bar
						$('#time_counter .counter_msg span').html(timerVal); //show countdown in box
						$('#life_counter .progress_bar').css('left', lifeleftPX+'%' ); //colored bar
						$('#life_counter .counter_msg span').html(lifeVal); //show countdown in box
						startGame();
				}else {
					// $('#gameResult_wrap each').remove();
					$(this).html('Use keypad!');
						startGame();
				}
		});

		$('#user_val').keyup(function(event){
			if(event.keyCode == 13){
				var res = (isNaN(parseInt($(this).val())) == true) ? 0 : parseInt($(this).val());
							checkResult(res);
							deleteTimer();
			}
		});
		
	});

		function startGame(){
			if( !gameStarted && lifeVal > 0){
				tick = new Date;
				timer(1000);
				gameStarted=true;
				makeNumbers(true);    
				gameStarted = 1;
				$('#user_val').prop("disabled", false).focus();
				startTime = tick.getTime();
			}
		
		}
		function makeNumbers(check){
			if(check){
				enterKey = true;
				gameLevel = Math.floor(questNr/5)+1;
				num1 = Math.floor(Math.random()*(gameLevel*10)+1);
				num2 = Math.floor(Math.random()*(gameLevel*10)+1);
				$('#work_field first').html(num1);    
				$('#work_field second').html(num2);
				$('#guest_data score score_val').html(pointsSum);
			}else{
				$('#work_field first').html('0');    
				$('#work_field second').html('0');
				$('#user_val').val('').blur();
			}
		}
	  	
		function timer(a){
			timerHandle=0;
			timerHandle = setInterval(showCountdown, a);
		}
		
		function deleteTimer(){
			clearInterval(timerHandle);
			timerHandle = 0;
		}
		
		function showCountdown(){
			timerleftPX = timerleftPX + 10;
			timerVal--;
			$('#time_counter .progress_bar').css('left', timerleftPX+'%' ); //colored bar
			$('#time_counter .counter_msg span').html(timerVal); //show countdown in box
			if(timerleftPX >= 0){
				deleteTimer();
			var res = (isNaN(parseInt($("#user_val").val())) == true) ? 0 : parseInt($("#user_val").val());
				checkResult(res);
			}
		}
		  
		function checkResult(res){
				tock = new Date;
			var endTime = tock.getTime();
			timeSpend = (endTime - startTime)/1000; //seconds
			resCorrect = num1 + num2;
			++questNr;
			if(resCorrect == res){
				ansCorrect(timeSpend,resCorrect,res);
			} else {
				//answer not correct
				ansNotCorrect(timeSpend,resCorrect,res);
			}
		}
	
		//if answer correct    
		function ansCorrect(timeSpend,resCorrect,res){
			messanger('Well done!','green');
			addLastResult(timeSpend, resCorrect, res, 'green', points());
			cleanUp();                
		}
		
		function points(){
			x = num1/10 + num2/10 + (10 - timeSpend);
			pointsSum=pointsSum+Math.floor(x);
			return Math.floor(x);
		} 

	//if answer incorrect
	function ansNotCorrect(timeSpend,resCorrect,res){
			messanger('Wrong answer!', 'red');     
			addLastResult(timeSpend,resCorrect,res,'red',0);
			if(lifeVal > 1){
				livesCounter(20,-1);
				cleanUp();
			} else {
				livesCounter(20,-1);
				messanger('Game over!', 'red'); 
				gameEnd();
			}
		};
		
	// Lives counter
	function livesCounter(left_val, level){
		lifeleftPX = lifeleftPX + left_val;
		lifeVal = lifeVal + level;
		$('#life_counter .progress_bar').css('left', lifeleftPX+'%' ); 
		$('#life_counter .counter_msg span').html(lifeVal); 
			
	} 		
		
	function cleanUp(){ //clean, make zerro for all values
		deleteTimer();
			makeNumbers(false);
			timerleftPX = -100;
			timerVal = 10;
				$('#time_counter .progress_bar').css('left', timerleftPX+'%' ); //colored bar
				$('#time_counter .counter_msg span').html(timerVal); //show countdown in box
			gameStarted = false;
			setTimeout(startGame, 1000);	//<-- starting game 
	}

	//game end   
	function gameEnd(){
		deleteTimer();
		newGame = true;
		gameStarted = 1;
		makeNumbers(false);
			timerleftPX = 0;
			timerVal = 0;
				$('#time_counter .progress_bar').css('left', timerleftPX+'%' ); //colored bar
				$('#time_counter .counter_msg span').html(timerVal); //show countdown in box
			sendAjax();
					setTimeout(function(){
						messanger('Start New Game!','green');
					}, 3000);	//<-- starting game 
					
			$('#user_val').prop("disabled", true);
	}
	
	function sendAjax(){	//<-- function submiting current result to database via ajax call
		usrName     = $('#guest_name').val(); 
		score       = $('#guest_data score score_val').text();
		$.post( '/site/insert_result' , $.param({ 'name': usrName, 'score': score}),
		   function(data){
			if(data=='done'){
				getTopScore();
			}else{
				alert(data)
			}
		  });
			
	}	
			
	//messages
	function messanger(msg, class_passed){
			$('#start_button').html(msg).removeClass("green red blue").addClass(class_passed);
		}    
 
	function addLastResult(t,resCorrect,res,tclass,points){
			tableNum.push([questNr,num1,num2,resCorrect,res,t,points]);
			td  = '<num>'+questNr+'</num>';
			td += '<q1>'+num1+'</q1>';
			td += '<q2>'+'+'+'</q2>';
			td += '<q3>'+num2+'</q3>';
			td += '<q4>'+'='+'</q4>';
			td += '<correct>'+resCorrect+'</correct>';
			td += '<answer>'+res+'</answer>';
			td += '<spend>'+t+'s</spend>';
			td += '<point>'+points+'</point>';
			$('#gameResult_wrap').append('<each class="'+tclass+'">'+td+'</each>');
			$('#gameResult').show();
		}

	//--------------- BEGIN: not for game stuff -----------------
		
	function getTopScore(){
			var bb =$('#TopResults data');
			$.ajax({
				type: "GET",
				cache: false,
				beforeSend: function(){ bb.html('<div class="ajaxLoaderSmall"></div>').show(); },
				url: '/site/view_last_result',
				success: function(data) {
					if(data.length>0){
						bb.html(data);
						$('#TopResults').slideDown('slow');
					}else{
						$('#TopResults').hide();
					}
				}

			});


	}    
	   
		
