<template >
	<div class="row" v-if="editing">
		<form>
		  <div class="form-group">
		    <label for="firstName">First Name</label>
		    <input type="text" class="form-control" id="firstName" placeholder="First Name" v-model.lazy="user.first_name">
		  </div>
		  <div class="form-group">
		    <label for="lastName">Last Name</label>
		    <input type="text" class="form-control" id="lastName" placeholder="Last Name" v-model.lazy="user.last_name">
		  </div>
		  <div class="form-group">
		    <label for="birtheday">Birthday</label>
		    <input type="date" id="birthday" class="form-control" placeholder="Birthday">
			<button v-tooltip.top-center="'Just used to wish you happy birthday'">?</button>
		  </div>
		  <button @click.prevent="save">Save</button>  
		  <button @click.prevent="remove">Remove User</button>  
		</form>
		
		
	</div>
	<div class="row" v-else>
		<div class="col-sm-1">
			<p-check name="status" class="p-icon p-round p-smooth" color="success" off-color="danger" @change="toggleStatus" v-model="user.status">
			</p-check>
		</div>
	
		<div class="col-sm-2" v-if="user.canEdit" @click="editing = true">
			{{ user.last_name }}, {{ user.first_name }}
		</div>
		<div class="col-sm-2" v-else>
			{{ user.last_name }}, {{ user.first_name }}
		</div>
		<div class="col-sm-8">
			<form  v-on:submit.prevent="" v-if="user.anyoneCanEdit | user.canEdit">
				<span v-if="!editMessage" @click="editMessage=true">{{ user.message }}</span>
				<span v-if="editMessage"><input v-model="user.message"><button type=submit @click="save">Save</button></span>
			</form>
		</div>
		<div class="col-sm-1">
			<span v-if="user.winner==true">
				YAY
			</span>
		</div>
		
	</div>
	
</template>


<script>
export default {

  name: 'InoutEntry',
  props: {'user': {}, 'board': null, "endpoint": null},
  data () {
    return {
    	editing: false,
    	editMessage: false,
    }
  },
  methods: {
  	toggleStatus () {
  		axios.put(this.endpoint + this.board + "/inout/" + this.user.id + "/toggleStatus", this.user)
  		.then(({data}) => {
  			if(data.success) {
				this.$emit('updatedUser')
			}
  		});
  		this.$emit('updatedUser')
  	},
  	save () {
  		axios.patch(this.endpoint + this.board + "/inout/" + this.user.id, this.user)
        .then(({data}) => {
			if(data.success) {
				this.$emit('updatedUser')
			}
            this.editing = false;
            this.editMessage = false;
        });
  	},
  	remove () {
  		axios.delete(this.endpoint + this.board + "/inout/" + this.user.id)
  		.then(({data}) => {
  			if(data.success) {
				this.$emit('updatedUser')
			}
  		});
  	}	
  }
  
}
</script>

<style lang="css" scoped>

.p-icon {
	font-size: 1.3em;
}

</style>