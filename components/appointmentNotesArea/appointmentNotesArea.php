<style>
.note-textarea {
	width: 100%;
}

.margin-top {
	margin-top: 10px;
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
		class="form-control note-textarea dcf-input-text" 
		cols="300" 
		rows="3" 
		maxlength="{{MAX_NOTE_LENGTH}}" 
		ng-maxlength="MAX_NOTE_LENGTH">
	</textarea>
	<span class="dcf-float-right dcf-mt-1">{{ noteToAddText ? noteToAddText.length : 0 }}/{{MAX_NOTE_LENGTH}}</span>
	<button class="dcf-btn dcf-btn-primary dcf-mt-3" 
		ng-click="addNote(noteToAddText)"
		ng-disabled="noteToAddText == null || noteToAddText.length <= 0">
		Add
	</button>

</div>