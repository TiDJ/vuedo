<template>
<section class="content">
  <h1 v-if="this.categoryId = this.$route.params.hashid">Medias in {{singleCategory}}</h1>
  <h1 v-else>Medias</h1>
  <button type="button" @click="createMedia" class="btn btn-lg btn-primary btn-flat" style="margin-bottom: 15px;">
    Create media
  </button>
  <button type="button" @click="refresh" class="btn pull-right btn-lg btn-warning btn-flat" style="margin-bottom: 15px;">
    Refresh page
  </button>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">All medias</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Description</th>
            </tr>
            <tr v-for="media in medias">
              <td>{{media.hashid}}</td>
              <td class="col-md-3">{{media.model_id}}</td>
              <td class="col-md-3">{{media.file_name}}</td>
            </tr>
          </table>
          <div>
            <v-paginator :resource.sync="medias" :resource_url="resource_url" :options="options"></v-paginator>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
</section>
</template>

<script>
import VuePaginator from 'vuejs-paginator/dist/vuejs-paginator.min.js'
import { stack_bottomright, show_stack_success, show_stack_error, show_stack_info } from '../Pnotice.js'

export default {
  components: {
    VPaginator: VuePaginator,
  },
  created () {
  },
  ready () {
  },
  data () {
    return {
      options: {
        remote_data: 'data',
        remote_current_page: 'meta.pagination.current_page',
        remote_last_page: 'meta.pagination.total_pages',
        remote_next_page_url: 'meta.pagination.links.next',
        remote_prev_page_url: 'meta.pagination.links.previous'
      },
      medias: [],
    }
  },
  methods: {
    deleteMedia (media) {
      let self = this
      swal({
        title: 'Are you sure?',
        text: 'You will not be able to recover this media!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it',
      }).then(function() {
        self.medias.$remove(media)
        self.$http.delete('/api/medias/' + media.hashid, media).then(function (response) {
          swal(
            'Deleted!',
            'Your media has been deleted.',
            'success'
          );
        }, function (response){
          show_stack_error('Deletion of media went wrong.', response)
        })
      }, function(dismiss) {
        // dismiss can be 'cancel', 'overlay', 'close', 'timer'
        if (dismiss === 'cancel') {
          swal(
            'Cancelled',
            'Your post is safe :)',
            'error'
          );
        }
      });
    },
    createMedia () {
      this.$http({url: '/api/medias', method: 'POST'}).then(function (response) {
        show_stack_info('Created', response)
        this.$router.go('/medias/'  + response.data.hashid + '/edit')
      })
    },
    refresh () {
      location.reload();
    }
  },
  computed: {
    resource_url: function () {
      return '/api/medias'
    },
    /*singleCategory: function () {
      for (var i = 0; i < this.options2.length; i++) {
         if (this.options2[i].hashid === this.categoryId) {
          return this.options2[i].name
        }
      }
    }*/
  },
}
</script>
