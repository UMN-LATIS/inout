<template>
	<div class="row">
		<form class="col-sm-12">
			<div class="form-row">
				<div class="col-sm-3">
					<input type="text" class="form-control" id="firstName" placeholder="First Name" v-model.lazy="user.first_name">
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="lastName" placeholder="Last Name" v-model.lazy="user.last_name">
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="email" placeholder="Email" v-model.lazy="user.email">
				</div>
				<div class="col-sm-3">
					<input  class="form-control" id="phone" placeholder="Phone Number" type="tel" v-model.lazy="user.phone">
				</div>
			</div>
			<div class="form-row">
				<div class="col-sm-3">
					<input type="text" class="form-control" id="office" placeholder="Office" v-model.lazy="user.office">
				</div>
				
				<div class="col-sm-3">
					<input type="url" class="form-control" id="calendarLink" placeholder="Calendar Link" v-model.lazy="user.calendar_link">
				</div>
				<div class="col-sm-3 input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">Birthday:</span>
					</div>
					<input type="date" id="birthday" v-model.lazy="user.birthday" class="form-control" placeholder="Birthday (YYYY-MM-DD)">
					<div class="input-group-append">
						<span v-tooltip.top-center="'Just used to wish you happy birthday. You don\'t need to give the real year.'" class="input-group-text">?</span>
					</div>
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="slackUser" placeholder="Slack User Name" v-model.lazy="user.slack_user">
				</div>
			</div>
			<div class="form-row">
				<div class="col-sm-3">
					 <TypeAhead
      :src="teamsURL"
      :getResponse="getResponse" v-model.lazy="user.team" placeholder="Team"
    ></TypeAhead>
					<!-- <input type="text" class="form-control" id="team" placeholder="Team" v-model.lazy="user.team"> -->
				</div>
			</div>
			<div class="form-row form-inline">
					<label for="input" class="col-sm-2 control-label">Check-In URL:</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" style="width: 100%" v-model="checkInURL" @click="$event.target.select()">
					</div>
					<label for="input" class="col-sm-2 control-label">Check-Out URL:</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" style="width: 100%" v-model="checkOutURL" @click="$event.target.select()">
					</div>
			</div>
			
			<div class="form-row" v-if="boardadmin">
				<div class="checkbox">
					<label>
						<input type="checkbox" v-model="user.isAdmin">
						Board Admin
					</label>
				</div>
			</div>
			<div class="form-row">
				<div class="col">
					<button @click.prevent="save" class="btn btn-primary">Save</button>  
					<button @click.prevent="remove" class="btn btn-danger">Remove User</button>  
				</div>
			</div>
		</form>
	</div>
</template>

<script>

import TypeAhead from 'vue2-typeahead';

export default {

	name: 'EditUser',
	props: ['user', 'boardadmin', 'board', "endpoint"],
	components: { TypeAhead},
	computed: {
		teamsURL: function() {
			return this.endpoint + this.board + "/inout/getTeams";
		},
		checkInURL: function() {
			return "http://" + window.location.hostname + this.endpoint + this.board + "/inout/" + this.user.id + "/in/" + this.user.userHash;
		},
		checkOutURL: function() {
			return "http://" + window.location.hostname+ this.endpoint + this.board + "/inout/" + this.user.id + "/out/" + this.user.userHash;
		}
	},
	methods: {
		getResponse(response) {
			console.log(response);
			return Object.values(response.data);
		},
		save() {
			this.$emit('update:user', this.user);
			this.$emit('save');
		},
		remove() {
			this.$emit('remove');
		}
	},
	data () {
		return {

		}
	}
}
</script>

<style lang="css" scoped>

.form-row {
	margin-top: 10px;
}

</style>