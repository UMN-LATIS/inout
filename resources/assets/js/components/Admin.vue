<template>
	<div>
		<input type="text" v-model="newUser">
		<button @click="save">Save</button>
	</div>
</template>

<script>
export default {

  name: 'Admin',
  props: ["board"],
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
			}
  		});
  	}
  }
}
</script>

<style lang="css" scoped>
</style>