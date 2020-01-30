(function () {
	'use strict';
	/* global window document HTMLElement */

	var layover		= document.getElementsByClassName('layover-link'),
		close		= document.getElementsByClassName('close'),
		submit		= document.getElementById('submit'),
		box			= document.getElementsByClassName('box'),
		w 	 		= window,
		projects 	= document.querySelectorAll('.project a');

	// Open Layover
	function openLayover(evt) {
		var data = evt.currentTarget.getAttribute('data-target');
		var	target = document.getElementById(data);
		if(!target.classList.contains('open')) {
			target.classList.add('open');
			document.getElementsByTagName('html')[0].classList.add('layover');
		}
	}

	// Close Layover
	function closeLayover(evt) {
		var data = evt.currentTarget.getAttribute('data-target');
		var	target = document.getElementById(data);
		setTimeout(function() {
			if(target.classList.contains('open')) {
				target.classList.remove('open');
				document.getElementsByTagName('html')[0].classList.remove('layover');
			}
		}, 100);
	}

	// set EventListener
	for (var i = layover.length - 1; i >= 0; i--) {
		layover[i].addEventListener('click', openLayover);
	}
	for (var i = close.length - 1; i >= 0; i--) {
		close[i].addEventListener('click', closeLayover);
	}

	function scrollIntoViewStart () {
		for (var i = projects.length - 1; i >= 0; i--) {
			var boundingTop = projects[i].getBoundingClientRect().top;

			if(boundingTop <= window.innerHeight) {
				projects[i].parentElement.style.animationDelay = (.4+i/4) + 's';
				projects[i].parentElement.classList.add('animate-start');
			}
		}
	}

	function scrollIntoView () {
		for (var i = projects.length - 1; i >= 0; i--) {
			var boundingTop = projects[i].getBoundingClientRect().top;

			if(boundingTop <= window.innerHeight) {
				projects[i].parentElement.classList.add('animate-start');
			}
		}
	}

	window.addEventListener('load', scrollIntoViewStart);
	window.addEventListener('scroll', scrollIntoView);


}());