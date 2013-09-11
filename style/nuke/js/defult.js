function Defult(){
	this.GetId = function(id){
		return document.getElementById(id);
	};
	
	this.NewPage = function(url){
		window.location.assign(url);
	};
}