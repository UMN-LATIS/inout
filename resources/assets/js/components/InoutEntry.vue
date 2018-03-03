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
		<div class="col-sm-6 col-md-3">
			<p-check name="status" v-bind:class="{ 'p-locked': !user.canEdit }" class="p-icon p-round p-smooth" color="success" off-color="danger" @change="toggleStatus" v-model="user.status" >
				<i slot="extra" class="icon fa fa-check"></i>
			</p-check>
			<span  v-if="user.canEdit" @click="editing = true" class="username">
				{{ user.last_name }}, {{ user.first_name }}
			</span>
			<span  v-else class="username">
				{{ user.last_name }}, {{ user.first_name }}
			</span>
		</div>
		<div class="col-7">
			<span v-if="user.anyoneCanEdit | user.canEdit">
				<span v-if="!editMessage">
					<span v-if="user.message.length > 1">
						{{ user.message}}
					</span>
					<span @click="editMessage=true" class="pull-right">
						edit
					</span>
				</span>
				<span v-if="editMessage"><input v-model="user.message"><button type=submit @click="save">Save</button></span>
				
			</span>
			<span v-else>
				{{ user.message }}
			</span>
		</div>
		<div class="col-2">
			<span class="pull-right" v-if="user.winner==true">
				YAY
			</span>
			<span class="pull-right" v-if="user.earlyBird==true">
				Early Bird
			</span>
			<span class="pull-right" v-if="user.happyBirthday==true">
				Happy Birthday
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

.username {
	font-weight: bold;
}

.p-icon {
	font-size: 1.3em;
}

.p-icon {
	margin-right: 5px;
	margin-left: 5px;
}

</style>