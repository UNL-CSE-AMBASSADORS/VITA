<style>
.note-textarea {
	width: 100%;
}

.margin-top {
	margin-top: 0.5em;
}
</style>

<div id="appointmentNotesArea" ng-cloak>
	<h3>Notes</h3>
	<ul>
		<!-- TODO: MAKE NOTE AREA LOOK BETTER -->
		<li ng-repeat="note in notes">{{note.note}} -- {{note.createdByFirstName}} {{note.createdByLastName}} ({{note.createdAt}})</li>
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
	<span class="wdn-pull-right margin-top">{{ noteToAddText ? noteToAddText.length : 0 }}/{{MAX_NOTE_LENGTH}}</span>
	<button class="wdn-button wdn-button-triad margin-top" 
		ng-click="addNote(noteToAddText)"
		ng-disabled="noteToAddText == null || noteToAddText.length <= 0">
		Add
	</button>

</div>