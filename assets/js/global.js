class VitaEventHandler {
	constructor(s, e, h) {
		this.selector = s;
		this.event = e;
		this.handler = h;
	}
}

var vitaEventHandlers = [];

function addEventHandler(s, e, h) {
	vitaEventHandlers.push(new VitaEventHandler(s, e, h));
}

function rebindEventHandlers() {
	vitaEventHandlers.forEach(function(veh) {
		$(veh.selector).off(veh.event);
		$(veh.selector).on(veh.event, veh.handler);
	});
}