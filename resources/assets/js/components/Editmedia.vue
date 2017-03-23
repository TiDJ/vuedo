<template>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <a  href="/blog/{{media.url}}" target="blank"  class="btn btn-lg btn-flat btn-success" role="button" style="margin-bottom: 15px;">
                    View Media
                </a>
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Media Editor
                            <small>Markdown Editor</small>
                        </h3>
                    </div>
                    <div class="box-body pad">
                        <div class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title" class="col-sm-1 control-label">Title</label>
                                <div class="col-sm-11">
                                    <input class="form-control" id="title" placeholder="title" v-model="media.title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-1 control-label">Description</label>
                                <div class="col-sm-11">
                                    <textarea id="mdeditor" name="content"></textarea>
                                </div>
                            </div>
                            <button type="button" @click="updateMedia(media)" class="btn btn-lg btn-success btn-flat pull-right">Save media</button>
                            <button class="btn btn-danger btn-flat btn-lg"  @click="deleteMedia(media)">Delete</button>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-->
        </div>
        <!-- ./row -->
    </section>
</template>
<script>
import SimpleMDE from 'simplemde'
import {stack_bottomright, show_stack_success, show_stack_error} from '../Pnotice.js'

export default {
    created() {
        this.mediaId = this.$route.params.hashid
        this.fetchMedia()
    },
    ready(){
        this.simplemde = new SimpleMDE({
            element: document.getElementById("mdeditor"),
            spellChecker: false,
        });
    },
    data () {
        return {
            media: {
            },
            mediaId: '',
            simplemde: ''
        }
    },
    methods: {
        fetchMedia () {
            this.$http({
                url: '/api/medias/unique/' + this.mediaId,
                method: 'GET'
            }).then(function (response) {
                console.log(response)
                this.$set('media', response.data)
                this.simplemde.value(this.media.description)
            })
        },
        updateMedia (media) {
            media.description = this.simplemde.value()
            return new Promise((resolve, reject) => {
                this.$http.patch('/api/medias/' + media.hashid, media).then((response) => {
                    show_stack_success('media saved!', response)
                    resolve()
                }, function (response) {
                    show_stack_error('Failed to save media!', response)
                    reject()
                });
            })
        },
        deleteMedia (media) {
            let self = this
            swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this media!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
            }).then(function () {
                self.$http.delete('/api/medias/' + media.hashid, media).then(function (response) {
                    self.$router.go('/medias')
                    swal(
                        'Deleted!',
                        'Your media has been deleted.',
                        'success'
                    );
                }, function (response) {
                    show_stack_error('Deletion of media went wrong.', response)
                })
            }, function (dismiss) {
                // dismiss can be 'cancel', 'overlay', 'close', 'timer'
                if (dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Your media is safe :)',
                        'error'
                    );
                }
            });
        }
    }
}
</script>
