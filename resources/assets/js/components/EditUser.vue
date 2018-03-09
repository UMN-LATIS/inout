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
				<div class="col-sm-4">
					<input type="text" class="form-control" id="office" placeholder="Office" v-model.lazy="user.office">
				</div>
				
				<div class="col-sm-4">
					<input type="url" class="form-control" id="calendarLink" placeholder="Calendar Link" v-model.lazy="user.calendar_link">
				</div>
				<div class="col-sm-4 input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">Birthday:</span>
					</div>
					<input type="date" id="birthday" v-model.lazy="user.birthday" class="form-control" placeholder="Birthday (mm/dd)">
					<div class="input-group-append">
						<span v-tooltip.top-center="'Just used to wish you happy birthday. We don\'t need the year.'" class="input-group-text">?</span>
					</div>
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

export default {

	name: 'EditUser',
	props: ['user'],
	methods: {
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