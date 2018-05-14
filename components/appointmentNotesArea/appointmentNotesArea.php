<style>

</style>

<!-- TODO: TRY TO PULL OUT THE 255 INTO LIKE A MAX_LENGTH VARIABLE SOMEWHERE -->
<div id="appointmentNotesArea" ng-cloak>
	<h3>Add a Note</h3>
	<textarea ng-model="addNoteMessage" 
		placeholder="-- Expanation --" 
		class="form-control" 
		cols="300" 
		rows="3" 
		maxlength="255" 
		ng-maxlength="255">
	</textarea>
	<span class="wdn-pull-right">{{ addNoteMessage ? addNoteMessage.length : 0 }}/255</span>
	<button class="wdn-button wdn-button-brand" 
		ng-click="addNote(addNoteMessage)">
		Add Note
	</button>

</div>