/*
 _________     ____   ____      ____   ____    ____   ___________
|         \   |    | |    |    |    | |    |  /   /  |     ______|
|          \  |    | |    |    |    | |    | /   /   |    |______
|    |\     \ |    | |    |    |    | |    |/   /    |     ______|
|    | \     \|    | |    |____|    | |    |\   \    |    |______
|    |  \          | |              | |    | \   \   |           |
|____|   \_________| |______________| |____|  \___\  |___________|

input.js createt start 08-03-2013
*/

var InsertPlace = 0;

function GetId(id){
	return document.getElementById(id);
}

function SetInputInFocus(id){
	GetId(id).focus();
}

function start(){}

var SmylieList = new Array();
SmylieList[0] = "[b]#[/b]";
SmylieList[1] = "[u]#[/u]";
SmylieList[2] = "[i]#[/i]";
SmylieList[3] = "[url=#]Insert text here![/url]";

function AddSmylie(num){
	if(num == -1){
		num = 0;
	}
	
	var Smylie =  SmylieList[num];
	var Input = GetId('mess');
	InsertText(Input,Smylie)
}

function InsertText(Input,Tekst){
	var NextIconPlace = GetIconPlace(Tekst);
	Tekst = Tekst.replace("#","");
	Input.focus();
	if(Input.createTextRange){
		document.selection.createRange().text += Tekst;
	}else if(Input.setSelectionRange){
		var Len = Input.selectionEnd;
		InsertPlace = Len + NextIconPlace;
		Input.value = Input.value.substr( 0, Len ) + Tekst + Input.value.substr( Len );
		Input.setSelectionRange(InsertPlace,InsertPlace);
	}
}

function GetIconPlace(bb){
	var place = bb.indexOf("#");
	return place;
}