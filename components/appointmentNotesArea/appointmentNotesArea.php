<style>
.note-textarea {
	width: 100%;
}
</style>

<div id="appointmentNotesArea" ng-cloak>
	<h3>Notes</h3>
	<ul>
		<!-- TODO: SHOULD INCLUDE THE CREATED BY USERNAME AND THE CREATED AT -->
		<!-- TODO: MAKE NOTE AREA LOOK BETTER -->
		<li ng-repeat="note in notes">{{note.note}} by {{note.createdByFirstName}} {{note.createdByLastName}} at {{note.createdAt}}</li>
	</ul>

	<h4>Add a Note</h4>
	<textarea ng-model="noteToAddText" 
		placeholder="-- Expanation --" 
		class="form-control note-textarea" 
		cols="300" 
		rows="3" 
		maxlength="{{MAX_NOTE_LENGTH}}" 
		ng-maxlength="MAX_NOTE_LENGTH">
	</textarea>
	<span class="wdn-pull-right">{{ noteToAddText ? noteToAddText.length : 0 }}/{{MAX_NOTE_LENGTH}}</span>
	<button class="wdn-button wdn-button-triad" 
		ng-click="addNote(noteToAddText)"
		ng-disabled="noteToAddText == null || noteToAddText.length <= 0">
		Add
	</button>

</div>