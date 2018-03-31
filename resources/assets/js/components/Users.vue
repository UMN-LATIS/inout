
<template>
    <div class="col-sm-12">

        <h1 class="display-3">{{ board.public_title }}</h1>
        <p class="lead">{{ board.announcement_text}}</p>
        <div class="row " v-if="allIn">
            <div class="col headerRow">
            <h1 class="headerText">Wow!</h1>
            <p class="headerSmall">Everyone is here!</p>
            </div>
        </div>
        
        <div class="clearfix">
            <select v-model="filterList" class="pull-right form-control btn-mini col-sm-3">
            	<option value="all">Everyone</option>
            	<option value="in">Only In</option>
            	<option value="out">Only Out</option>
            </select>
        </div>
    	<transition-group name="fade">
    		<inoutentry v-on:updatedUser="updatedUser" v-for="user in sortedAlphabetically" :key="user.id" :board="board.unit" :boardadmin="boardadmin" :endpoint="endpoint" :user="user" class="inout-entry"></InoutEntry>
    	</transition-group>

        <admin :board="board.unit" :boardId="board.id" v-if="boardadmin" v-on:updatedUser="updatedUser"></admin>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                endpoint: '/api/',
                filterList: "all",
                allIn: false
            };
        },
        props: ['board', 'boardadmin'],
        created() {
            this.fetch();
        },
        mounted: function() {
            Echo.private(this.board.unit)
            .listen('.UserChangedEvent', (e) => {
                this.fetch();
            });
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
            updatedUser(e) {

            },
            fetch() {
                axios.get(this.endpoint + this.board.unit + "/inout")
                    .then(({data}) => {
                        this.users = data.data;
                        if(this.users.filter(function(user) {
                            return user.status == false;
                        }).length == 0) {
                            this.allIn = true;
                        }
                        else {
                            this.allIn = false;
                        }
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

select.btn-mini {
    height: auto;
    line-height: 14px;
}

.jumbotron {
    margin-top: 20px;
}

.headerRow {
    text-align: center;
    animation-name: hideRow;
    animation-duration: 1s;
    animation-delay: 7s;
    animation-fill-mode: forwards;
    overflow:hidden;
}



@keyframes fontAnimation {
    0%   {font-size: 0px;}
    25%  {font-size:160px;}
    50%  {font-size:140px;}
    100% {font-size:150px;}
}

@keyframes hideRow {
    0%   {max-height: 200px; overflow:hidden; height:auto; display:block;}
    100% {max-height: 0px; overflow:hidden; height:auto; display:block;}
}


.headerText {
    font-family: 'Amatic SC', cursive;
    color: rgba(00,150,150,1);
    font-size: 150px;
    animation-name: fontAnimation;
    animation-duration: 2s;

}
.headerSmall {
    font-size: 3.5em;
    font-family: 'Amatic SC', cursive;

}
</style>
