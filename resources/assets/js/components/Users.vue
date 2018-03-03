
<template>
    <div class="col-sm-12">
        <select v-model="filterList">
        	<option value="all">All</option>
        	<option value="in">Only In</option>
        	<option value="out">Only Out</option>
        </select>
    	<transition-group name="fade">
    		<inoutentry v-on:updatedUser="fetch" v-for="user in sortedAlphabetically" :key="user.id" :board="board" :endpoint="endpoint" :user="user" class="inout-entry"></InoutEntry>

    	</transition-group>

    	<button v-on:click="fetch">Fetch</button>
        <admin :board="board" v-if="boardadmin" v-on:updatedUser="fetch"></admin>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                endpoint: '/api/',
                filterList: "all"
            };
        },
        props: ['board', 'boardadmin'],
        created() {
            this.fetch();
        },
        computed: {
  			sortedAlphabetically: function () {
  				var filteredList = this.filterList;
    			return _.orderBy(this.users.filter(item => {
    				if(filteredList == "in" && item.status == true) {
    					return item;
    				}
    				else if(filteredList == "out" && item.status == false) {
    					return item;
    				}
    				else if(filteredList == "all") {
    					return item;
    				}
    				
    				
    			}), [user => user.last_name.toLowerCase()]);
  			}
		},
        methods: {
            fetch() {
                axios.get(this.endpoint + this.board + "/inout")
                    .then(({data}) => {
                        this.users = data.data;
                    });
            },
        }

    }
</script>

<style>
.inout-entry {
    margin-top: 10px;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px lightgray solid;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

</style>
