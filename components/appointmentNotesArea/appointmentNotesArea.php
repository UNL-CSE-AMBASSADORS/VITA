<style>

</style>

<!-- TODO: TRY TO PULL OUT THE 255 INTO LIKE A MAX_LENGTH VARIABLE SOMEWHERE -->
<div id="appointmentNotesArea" ng-cloak>
	<h3>Notes</h3>
	<div>
		<ul>
			<!-- TODO: SHOULD INCLUDE THE CREATED BY USERNAME AND THE CREATED AT -->
			<!-- TODO: MAKE NOTE AREA LOOK BETTER -->

			<li ng-repeat="note in notes">{{note.note}} by {{note.createdByFirstName}} {{note.createdByLastName}} at {{note.createdAt}}</li>
		</ul>
	</div>

	<h3>Add a Note</h3>
	<textarea ng-model="noteToAddText" 
		placeholder="-- Expanation --" 
		class="form-control" /
		cols="300" 
		rows="3" 
		maxlength="255" 
		ng-maxlength="255">
	</textarea>
	<span class="wdn-pull-right">{{ noteToAddText ? noteToAddText.length : 0 }}/255</span>
	<button class="wdn-button wdn-button-triad" 
		ng-click="addNote(noteToAddText)">
		Add Note
	</button>

</div>