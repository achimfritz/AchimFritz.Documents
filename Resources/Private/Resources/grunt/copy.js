/*jslint node: true */
/*global module*/
'use strict';
module.exports = {
    main: {
        files: [
            {expand: true, cwd: 'bower_components/bootstrap/fonts', src: '**', dest: '../../Public/Build/fonts/'}
        ]
    }
};
