<style>

</style>

<!-- TODO: TRY TO PULL OUT THE 255 INTO LIKE A MAX_LENGTH VARIABLE SOMEWHERE -->
<div id="appointmentNotesArea" ng-cloak>
	<h1>Add a Note</h1>
	<textarea ng-model="sharedProperties.note" 
		placeholder="-- Expanation --" 
		class="form-control" 
		cols="300" 
		rows="3" 
		maxlength="255" 
		ng-maxlength="255">
	</textarea>
	<span class="wdn-pull-right">{{ sharedProperties.note ? sharedProperties.note.length : 0 }}/255</span>
	<button class="wdn-button wdn-button-brand" 
		ng-click="addAppointmentNote(sharedProperties.note)">
		Add Note
	</button>
</div>