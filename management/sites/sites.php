<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
	<div class="wdn-grid-set">
		<!-- Site select list -->
		<div class="wdn-col-one-fourth">
			<ul class="unstyled-list wdn-list-reset">
				<li class="site-list-element"
					ng-repeat="site in sites"
					ng-click="selectSite(site)">
					<a>{{site.title}}</a>
				</li>
			</ul>
		</div>

		<!-- Site Information Area -->
		<div class="wdn-col-three-fourths">
			<!-- Shown is a site is selected -->
			<div ng-if="selectedSite != null">
				<p>{{selectedSite.title}}</p>
			</div>

			<!-- Shown if no site is selected -->
			<div ng-if="selectedSite == null" class="centered-text">
				<p>Select a site on the left.</p>
			</div>
		</div>
	</div>
</div>