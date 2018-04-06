<template>
    <div class="component-container">
        <a class="btn btn-primary" @click="show()" v-if="post_id">Update record</a>
        <a class="btn btn-primary" @click="show()" v-if="!post_id">Add new record</a>

        <div class="container">
          <div v-if="showModal">
              <transition name="modal">
                <div class="modal-mask">
                  <div class="modal-wrapper">
                    <div class="modal-container">

                      <div class="modal-header">
                        <div v-if="post_id">Updating record [{{ post_id }}]</div>
                        <div v-if="!post_id">Adding new record</div>
                      </div>

                      <div class="modal-body">

                        <form class="post-form col-md-12 col-lg-12 col-xs-12">
                            <div class="post-form__row">
                                <label for="post-form__title">Title</label>
                                <input
                                        type="text"
                                        name="title"
                                        v-model="title"
                                        class="form-control col-md-12 col-lg-12 col-xs-12"
                                        id="post-form__title">
                            </div>
                            <div class="post-form__row">
                                <label for="post-form__text">Description</label>
                                <textarea
                                        name="text"
                                        class="form-control col-md-12 col-lg-12 col-xs-12"
                                        id="post-form__text"
                                        v-model="text"></textarea>
                                </label>
                            </div>
                        </form>

                      </div>

                      <div class="modal-footer">
                        <div class="post-form__row">
                            <a
                                    class="btn btn-primary comment-form__button-save"
                                    v-on:click="submit">
                                    Submit
                            </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </transition>
          </div>
        </div>

    </div>
</template>

<script>
    export default {
        methods: {
          show: function () {
            let self = this
            this.getPostData()
              .then(function (response) {
                self.showModal = true
              })
          },
          getPostData: function () {
            if (this.post_id) {
              let self = this
              return new Promise((resolve, reject) => {
                this.$http.get('/get-post/?id=' + this.post_id)
                  .then(function (response) {
                    self.id = response.data.id
                    self.title = response.data.title
                    self.text = response.data.text
                    return resolve(response)
                  })
                  .catch(() => reject)
              })
            } else {
              return new Promise((resolve, reject) => {
                    return resolve([])
              })
            }
          },
          sendPostData: function () {
            let self = this
            return new Promise((resolve, reject) => {
              let params = {
                id: self.post_id,
                title: self.title,
                text: self.text
              }
              let url = self.post_id ? '/update-post/' : '/create-post/'
              this.$http.post(url, params)
                .then(function (response) {
                  location.reload()
                  return resolve(response)
                })
                .catch(() => reject)
            })
          },
          submit: function () {
            this.sendPostData()
          }
        },
        props: ['post_id'],
        data: function () {
          return {
            id: '',
            title: '',
            text: '',
            showModal: false,
          }
        },
        mounted: function () {
        }
    }
</script>

<style lang="stylus">
  .post-form
    border-left 1px solid light-gray
    display block
    margin 0.2rem 0
    padding 0.6rem

    &__row
      cursor pointer
      font-weight 600

</style>