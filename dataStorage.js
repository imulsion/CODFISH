(function(){
	//Creates an empty array that will store the inputs to the text fields.
	var weekData = [0, 0, 0, 0, 0, 0, 0];

	//Creates the submitButton object that is linked to the button in the html file.
	var submitButton = document.querySelector("#submit");

	//Button listens for "click" event, and calls clickHandler function if event is triggered.
	submitButton.addEventListener("click", clickHandler, false);

	//Cursor becomes pointer when hovering over the button.
	submitButton.style.cursor = "pointer";

	//This is the function called when the button is clicked.
	function clickHandler() {

		//Creates an object linked to each input field from the html file according to the id.
		var mondayData = document.querySelector("#monday");
		var tuesdayData = document.querySelector("#tuesday");
		var wednesdayData = document.querySelector("#wednesday");
		var thursdayData = document.querySelector("#thursday");
		var fridayData = document.querySelector("#friday");
		var saturdayData = document.querySelector("#saturday");
		var sundayData = document.querySelector("#sunday");

		//Stores the inputs to the text fields into the weekData array
		weekData[0] = mondayData.value;
		weekData[1] = tuesdayData.value;
		weekData[2] = wednesdayData.value;
		weekData[3] = thursdayData.value;
		weekData[4] = fridayData.value;
		weekData[5] = saturdayData.value;
		weekData[6] = sundayData.value;

		//Displays the contents of the weekData array in the console
		console.log(weekData);
	}
}());