<template >
	<div>
		<div class="row statusRow align-items-center" v-bind:id="user.id">
			<div class="col-sm-12 col-md-4 col-lg-3">
				<p-check name="status" v-bind:class="{ 'p-locked': !(user.canEdit | user.anyoneCanEdit) }" class="p-icon p-round " color="success" off-color="danger" @change="toggleStatus" v-model="user.status" >
					<i slot="extra" class="icon fa fa-check"></i>
				</p-check>
				<span class="username">
					<a href="" class="userClick" v-on:click.prevent="show = !show">
						{{ user.last_name }}, {{ user.first_name }}
					</a>
				</span>
				<span v-if="(user.anyoneCanEdit | user.canEdit) & !editMessage" @click="editMessage=true" class="pull-right editIconSpan">
							<i class="icon fa fa-pencil editIcon" title="edit"></i>
						</span>
			</div>
			<div class="col-md-8 col-lg-7 col-sm-12">
				<span v-if="user.anyoneCanEdit | user.canEdit">
					<span v-if="!editMessage">
						<span class="messageText" v-if="user.message.length > 1">
							{{ user.message}} <span class="lastUpdated">{{ user.lastUpdated }}</span>
						</span>
						
					</span>
					<span v-if="editMessage" class="input-group input-group-sm">
						<input class="form-control messageEntry" v-model="user.message" @keyup.enter="save" @keyup.esc="editMessage=false"/>
						<div class="input-group-append">
							<button type=submit class="btn btn-primary" @click="save">Save</button>
						</div>
					</span>

				</span>
				<span class="messageText" v-else>
					<span v-if="user.message.length > 1">
						{{ user.message }} <span class="lastUpdated">{{ user.lastUpdated }}</span>
					</span>
				</span>
			</div>
			<div class="col-2 d-none d-lg-block">
				<div class="pull-right badgeContainer" >
					<img src="/images/earlybird.svg" class="img-responsive badgeIcon" v-if="user.earlyBird==true" v-tooltip.top-center="'Early Bird!'"/>
					<img src="/images/winner.svg" class="img-responsive badgeIcon" v-if="user.winner==true" v-tooltip.top-center="'Today\'s random winner'"/>
					<img src="/images/cake.svg" class="img-responsive badgeIcon" v-if="user.happyBirthday==true" v-tooltip.top-center="'Happy BirthdaY!'"/>
				</div>
			</div>	
		</div>
		<transition name="slide-fade">
			<edituser v-if="show && user.canEdit" :board="board" :endpoint="endpoint" :boardadmin="boardadmin" :user.sync="user" v-on:remove="remove" v-on:save="save"></edituser>
			<viewuser v-if="show && !user.canEdit" :user.sync="user"></viewuser>
		</transition>

	</div>
</template>


<script>
export default {

	name: 'InoutEntry',
	props: {'user': {}, 'board': null, "endpoint": null, "boardadmin": null},
	data () {
		return {
			show: false,
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
		},
		save () {
			axios.patch(this.endpoint + this.board + "/inout/" + this.user.id, this.user)
			.then(({data}) => {
				if(data.success) {
					this.$emit('updatedUser')
				}
				// $(this.$el).find("a[data-toggle]").click();
				this.show = false;
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

.username a {
	color: black;
}

.p-icon {
	font-size: 1.5em;
}

.p-icon {
	margin-right: 5px;
	margin-left: 5px;
}

.editIcon {
	font-size: 1.2em;
}
.editIcon:hover {
	cursor: pointer;
	/*font-size: 1.5em;*/
}

.editIconSpan {
	padding-right: 10px;
}

.userClick:hover {
	cursor: pointer;
	text-decoration: underline;
}


.badgeIcon {
	width: 25px;
	max-height: 25px;
}

.badgeContainer {
	height: 100%;
}

.statusRow {
	min-height:30px;
}

.lastUpdated {
	font-size: 0.8em;
	color: gray;
}

.slide-fade-enter-active {
  overflow:hidden;
  transition:max-height 0.3s ease-out;
  max-height:600px;

  /*transition: all .5s ease;*/
}
.slide-fade-leave-active {
	overflow:hidden;
  transition:max-height 0.3s ease-out;
  max-height:600px;
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  /*transform: translateY(-60px);*/
  /*opacity: 0;*/
  /*opacity: 0;*/
  max-height:0;
}

@media only screen and (max-width: 767px) {
	.messageText {
		margin-top: 3px;
	}
}

</style>