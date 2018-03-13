<template>
	<div>
    <div class="row">
      <div class="col-sm-4">
        <input type="text" v-model="newUser" class="form-control" placeholder="Add user (internetId)">
      </div>
      <div class="col-sm-3">
        <button @click="save" class="btn btn-primary">Save</button>  
      </div>
		  
    </div>
    <div class="row">
      <div class="col-sm-12"><a v-bind:href="'/admin/boards/' + boardId + '/edit'">Edit Board</a> </div>
    </div>
	</div>
</template>

<script>
export default {

  name: 'Admin',
  props: ["board", "boardId"],
  data () {
    return {
    	newUser: ""
    }
  },
  methods : {
  	save() {
  		axios.post("/api/" + this.board + "/inout/createUser", {newUser: this.newUser})
  		.then(({data}) => {
  			if(data.success) {
				this.$emit('updatedUser')
        this.newUser = "";
			}
  		});
  	}
  }
}
</script>

<style lang="css" scoped>
</style>