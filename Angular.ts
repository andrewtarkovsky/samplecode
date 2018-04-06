"use strict";

import {Component, OnInit} from "angular2/core";
import {Router, RouteParams} from "angular2/router";
import {SpAlbum, SpAlbumService} from './../../services/album.service';
import {SpPhoto, SpPhotoService} from './../../services/photo.service';
import {SpOrganizerPhotoComponent} from './photo.organizer.component';

@Component({
    selector: 'sp-organizer-album',
    templateUrl: 'app/views/organizer/album/album-main.html',
    directives: [
        SpOrganizerPhotoComponent
    ]
})
export class SpOrganizerAlbumComponent implements OnInit {

    constructor(private routeParams:RouteParams, private albumService: SpAlbumService, private photoService: SpPhotoService) {}

    public selectedAlbum: SpAlbum = null;

    public photos: Array<SpPhoto> = [];

    ngOnInit() {
        var id = this.routeParams.get('id');
        this.albumService
            .getAlbum(id)
            .then(album => {
                this.selectedAlbum = album;

                return this.photoService.getPhotos(album.data.album_id);
            })
            .then(photos => {
                this.photos = photos
            })
    }

}