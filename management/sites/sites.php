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
			<!-- Shown if a site is selected and loaded from backend -->
			<div ng-if="selectedSite != null && siteInformation != null">
				<h4 class="clear-top">{{siteInformation.title}}</h4>
				<div class="wdn-grid-set-halves">
					<div class="wdn-col">
						<div><b>Address: </b>{{siteInformation.address}}</div>
						<div><b>Phone Number:</b> {{siteInformation.phoneNumber}}</div>
					</div>
					<div class="wdn-col">
						<div><b>Does Multilingual:</b> {{siteInformation.doesMultilingual ? 'Yes' : 'No'}}</div>
						<div><b>Does International:</b> {{siteInformation.doesInternational ? 'Yes' : 'No'}}</div>
					</div>
				</div>
			</div>

			<!-- Shown if no site is selected -->
			<div ng-if="selectedSite == null" class="centered-text">
				<p>Select a site on the left.</p>
			</div>
		</div>
	</div>
</div>