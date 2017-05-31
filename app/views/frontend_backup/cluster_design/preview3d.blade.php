<script src="{{URL}}/assets/js/canvas3d/three.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/requestAnimFrame.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/OrbitControls.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/Detector.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    var container;
    var camera, controls, scene, renderer;
    var WebGLSupported = isWebGLSupported();


    function build3D(object, callBack){

          // scene size
        var WIDTH = window.innerWidth;
        var HEIGHT = window.innerHeight;

        // camera
        var VIEW_ANGLE = 45;
        var ASPECT = WIDTH / HEIGHT;
        var NEAR = 1;
        var FAR = 500;
        var group_obj = new THREE.Group();

        scene = new THREE.Scene();
        scene.fog = new THREE.FogExp2( 0xcccccc, 0.0002);
        camera = new THREE.PerspectiveCamera( 30, 1000/550, 0.1,  80000 );
        camera.position.z = Math.max(object.width,object.height)*2;
        camera.position.x = Math.max(object.width,object.height)*-1.5;
        // lights
        light = new THREE.PointLight( 0xffffff);
        light.position.set( Math.max(object.width,object.height)*2, -Math.max(object.width,object.height)*2 , Math.max(object.width,object.height)*2 );
        scene.add( light );

        light2 = new THREE.PointLight( 0xffffff);
        light2.position.set(- Math.max(object.width,object.height)*2,Math.max(object.width,object.height)*2, -Math.max(object.width,object.height)*2 );
        scene.add( light2 );

        // ambientLight = new THREE.AmbientLight( 0x888888 );
        // scene.add( ambientLight );

        var directionalLightTopLeft = new THREE.DirectionalLight( 0xffffff,0.8);
        directionalLightTopLeft.position.set( -1, 1, 0 );
        scene.add( directionalLightTopLeft);

        var directionalLightBotRight = new THREE.DirectionalLight( 0xeeeeee,0.8);
        directionalLightBotRight.position.set( 1,-1, 0);
        scene.add( directionalLightBotRight);


        var directionalLightFront = new THREE.DirectionalLight( 0xffffff,0.6);
        directionalLightFront.position.set( 0,0,1);
        scene.add( directionalLightFront);


        var alpha = false;
        if( typeof callBack == 'function' ) {
            alpha = true;
        }
        // renderer
        renderer = WebGLSupported ? new THREE.WebGLRenderer( { antialias: true, alpha: alpha } ) : new THREE.CanvasRenderer();
        renderer.setClearColor( scene.fog.color, 0 );
        renderer.setSize( 1000, 550 );

        container = document.getElementById( 'preview_content' );
        container.appendChild( renderer.domElement );

        controls = new THREE.OrbitControls( camera, container );
        controls.damping = 7;
        controls.minDistance = 100;
        controls.maxDistance = 5 * object.width;



        // fillScene();
        @if ($product['product_type']==6 && $product['number_img'])
            object.color = 0xffffff;
        @endif
        var materialClass = WebGLSupported ? THREE.MeshLambertMaterial : THREE.MeshBasicMaterial;
        var back    = new materialClass( { color: 0xffffff, ambient: 0xffffff, overdraw: 0.5 } );
        if( !object.hasOwnProperty("points") ) {
            var k = 1;
            if( object.color ) {
                var left = right = top = bottom = new materialClass( { color: object.color, overdraw: 0.5 } );
            } else {
                var left    = new materialClass( { map: THREE.ImageUtils.loadTexture( object["images"].left, {}, function(){
                    render();
                    renderComplete(k++, Object.keys(object["images"]).length, callBack);
                }), transparent: true, color: 0xffffff } );
                var right   = new materialClass( { map: THREE.ImageUtils.loadTexture( object["images"].right, {}, function(){
                    render();
                    renderComplete(k++, Object.keys(object["images"]).length, callBack);
                }), transparent: true, color: 0xffffff } );
                var top     = new materialClass( { map: THREE.ImageUtils.loadTexture( object["images"].top, {}, function(){
                    render();
                    renderComplete(k++, Object.keys(object["images"]).length, callBack);
                }), transparent: true, color: 0xffffff } );
                var bottom  = new materialClass( { map: THREE.ImageUtils.loadTexture( object["images"].bottom, {}, function(){
                    render();
                    renderComplete(k++, Object.keys(object["images"]).length, callBack);
                }), transparent: true, color: 0xffffff } );
            }
            var center  = new materialClass( { map: THREE.ImageUtils.loadTexture( object["images"].center, {}, function(){
                render();
                renderComplete(k++, Object.keys(object["images"]).length, callBack);
            }), transparent: true, color: 0xffffff, ambient: 0xffffff});

            var materials = [
                right,      // Right side
                left,       // Left side
                top,        // Top side
                bottom,     // Bottom side
                center,     // Center side
                back,       // Back side
              ];
            var Pic3D =  new THREE.Mesh( new THREE.BoxGeometry( object.width, object.height, object.bleed, object.bleed, object.bleed, object.bleed ) ,  new THREE.MeshFaceMaterial( materials ));

           if( object.float_canvas != undefined ) {
                        var shape = new THREE.Shape();
                        var halfWidth = object.width / 2;
                        var halfHeight = object.height / 2;
                        var halfBleed = object.bleed / 2;
                        var x0, y0, x1, y1, x2, y2, x3, y3,
                            x4, y4, x5, y5, x6, y6, x7, y7;
                        x0 = -(halfWidth  + halfBleed);
                        y0 = -(halfHeight + halfBleed);

                        x1 = x0;
                        y1 = halfHeight + object.bleed * 2 + halfBleed;

                        x2 = halfWidth + object.bleed * 2 + halfBleed;
                        y2 = y1

                        x3 = x2
                        y3 = y0;

                        x4 = x2 - object.bleed;
                        y4 = y3 + object.bleed;

                        x5 = x4;
                        y5 = y2 - object.bleed;

                        x6 = x1 + object.bleed;
                        y6 = y5;

                        x7 = x6;
                        y7 = y4;

                        shape.moveTo(x0, y0);
                        shape.lineTo(x1, y1);
                        shape.lineTo(x2, y2);
                        shape.lineTo(x3, y3);
                        shape.lineTo(x4, y4);
                        shape.lineTo(x5, y5);
                        shape.lineTo(x6, y6);
                        shape.lineTo(x7, y7);
                        shape.lineTo(x4, y4);
                        shape.lineTo(x3, y3);
                        // material texture
                        var texture = new THREE.Texture( generateTexture() );
                        texture.needsUpdate = true; // important!
                        var geometry = new THREE.ExtrudeGeometry(shape, { amount: object.bleed + 10,
                                                                          bevelEnabled: false});
                        var frame_colour,ambientcolor;
                        if(objEdgeColor.color!=undefined)
                            frame_colour = objEdgeColor.color;
                        else
                            frame_colour = '0x996633';
                        if(frame_colour=='black'){
                            ambientcolor = "#1111111";
                            frame_colour = '#333333';
                        }else if(frame_colour=='white'){
                            ambientcolor = '#ffffff';
                        }else if(frame_colour=="#4E0E0E"){
                            ambientcolor = frame_colour;
                            frame_colour = '#733C3C';
                        }else
                            ambientcolor = 'transparent';

                        console.log(frame_colour);
                        //var mesh = new THREE.Mesh(geometry, new THREE.MeshBasicMaterial({/*color: frame_colour, ambient: 0xdddddd,*/ map: texture }));
                        var mesh = new THREE.Mesh(geometry, new THREE.MeshPhongMaterial({color:frame_colour, ambient:ambientcolor, specular: 0x050505,shininess: 100}));
                        mesh.dynamic = true;
                        mesh.position.set(-object.bleed, -object.bleed, -object.bleed);

                        var backShape =  new THREE.Mesh( new THREE.BoxGeometry( object.width + object.bleed*3, object.height +object.bleed*3, 1, 1, 1, 1 ) ,  new THREE.MeshBasicMaterial( {map: texture} ));
                        backShape.position.set(0,0,-(object.bleed));
                        var group = new THREE.Object3D();

                        group.add(mesh);
                        group.add(backShape);
                        scene.add(group);
            }
            scene.add( Pic3D );

        } else {
            var i;
            var arr_tmp = [];
            var shape = new THREE.Shape();
            var points = object.points["center"].points;
            var length = points.length;
            var shapePoint = [];
            if( length % 2 == 0 ) {
                for(i = 0; i < length; i++) {
                    if( i < length /2 ) {

                        var point = {x:points[i].x,y:points[i].y};
                        arr_tmp[i] = point;
                        var tmp = points[i].y;
                        points[i].y = points[(i+length/2)].y;
                        points[(i+length/2)].y = tmp;
                    }
                    var y = points[i].y;
                    if( !i ) {
                        shape.moveTo( points[i].x, y );
                    } else {
                        shape.lineTo( points[i].x, y );
                    }
                    shapePoint[i] = {x: points[i].x, y: y};
                }
            } else {
                 var point = {x:points[0].x,y:points[0].y};
                 arr_tmp.push(point);
                points[0].y =  Math.abs(points[1].y - points[0].y) * 2;
                for(i = 0; i < length; i++) {
                    var y = points[i].y;
                    if( !i ) {
                        shape.moveTo( points[i].x, y );
                    } else {
                        shape.lineTo( points[i].x, y );
                    }
                    shapePoint[i] = {x: points[i].x, y: y};
                }
            }

            var geometry = new THREE.ExtrudeGeometry(shape, { amount: 0,
                                                              bevelEnabled: false,
                                                              material: 0,
                                                              extrudeMaterial: 1});
            var k = 1;
            var texture = THREE.ImageUtils.loadTexture( object["images"].center, {}, function(){
                render();
                renderComplete(k++, Object.keys(object["images"]).length, callBack);
            } );
            var materialFront = new THREE.MeshBasicMaterial({ ambient: 0xffffff, transparent: true, map: texture });
            var color = object.color || 0xff0000;
            var materialSide = new THREE.MeshBasicMaterial({color: color, ambient: color});
            var materialBack = new THREE.MeshBasicMaterial({color: 0xffffff, ambient: 0xffffff});
            var materials = [materialFront, materialSide, materialBack];
            var material = new THREE.MeshFaceMaterial(materials);
            var mesh = new THREE.Mesh(geometry, material);
            for ( var face in mesh.geometry.faces ) {
                if (mesh.geometry.faces[ face ].normal.z < 0){
                    mesh.geometry.faces[ face ].materialIndex = 2;
                }
            }
            geometry.computeBoundingBox();
            var max     = geometry.boundingBox.max;
            var min     = geometry.boundingBox.min;

            var offset  = new THREE.Vector2(0 - min.x, 0 - min.y);
            var range   = new THREE.Vector2(max.x - min.x, max.y - min.y);

            geometry.faceVertexUvs[0] = [];
            var faces = geometry.faces;
            for (i = 0; i < geometry.faces.length ; i++) {
                var v1 = geometry.vertices[faces[i].a];
                var v2 = geometry.vertices[faces[i].b];
                var v3 = geometry.vertices[faces[i].c];
                geometry.faceVertexUvs[0].push([
                    new THREE.Vector2( ( v1.x + offset.x ) / range.x , ( v1.y + offset.y ) / range.y ),
                    new THREE.Vector2( ( v2.x + offset.x ) / range.x , ( v2.y + offset.y ) / range.y ),
                    new THREE.Vector2( ( v3.x + offset.x ) / range.x , ( v3.y + offset.y ) / range.y )
                ]);

            }
            geometry.uvsNeedUpdate = true;
            group_obj.add(mesh);
            // scene.add(mesh);


             // Back
            mesh_back = mesh.clone();
            mesh_back.position.set( 0, 0, -object.bleed );
            group_obj.add(mesh_back);

            // scene.add(mesh_back);

            $.each(object.points,function(key,obj){
                if(key!='center'){
                        var geometry = new THREE.BoxGeometry(1, 1, 0);
                        var m = {
                                color:0xffffff,
                                ambient: 0xffffff,
                            };
                        if( object.images[key] != undefined ) {
                            var texture = THREE.ImageUtils.loadTexture( object.images[key], {}, function(){
                                render();
                                renderComplete(k++, Object.keys(object["images"]).length, callBack);
                            });
                            m.map = texture;
                        }else if( object.color != undefined ){
                            m.color = object.color;
                            m.ambient = object.color;
                        }
                        var material = new THREE.MeshBasicMaterial(m);
                        if(shapePoint.length<6){
                            if(key=='top' ){
                                geometry.vertices[0].x = geometry.vertices[1].x = shapePoint[shapePoint.length-4].x;
                                geometry.vertices[0].y = geometry.vertices[1].y = shapePoint[shapePoint.length-4].y;
                                geometry.vertices[2].x = geometry.vertices[3].x =shapePoint[shapePoint.length-3].x;
                                geometry.vertices[2].y = geometry.vertices[3].y =shapePoint[shapePoint.length-3].y;
                                geometry.vertices[0].z = geometry.vertices[2].z = -object.bleed;
                            }

                            if(key=='top_right'){
                                geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-4].x;
                                geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-4].y;
                                geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-3].x;
                                geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-3].y;
                                geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }


                             if(key=='right'){
                                    geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-3].x;
                                    geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-3].y;
                                    geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-2].x;
                                    geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-2].y;
                                    geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }


                           if(key=='bottom'){
                                    geometry.vertices[0].x = geometry.vertices[1].x = shapePoint[shapePoint.length-2].x;
                                    geometry.vertices[0].y = geometry.vertices[1].y = shapePoint[shapePoint.length-2].y;
                                    geometry.vertices[2].x = geometry.vertices[3].x =shapePoint[shapePoint.length-1].x;
                                    geometry.vertices[2].y = geometry.vertices[3].y =shapePoint[shapePoint.length-1].y;
                                    geometry.vertices[1].z = geometry.vertices[3].z = -object.bleed;
                            }

                            if(key=='bottom_right'){
                                 geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-3].x;
                                 geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-3].y;
                                 geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-2].x;
                                 geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-2].y;
                                 geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }


                            if(key=='left' || key=='top_left'){
                                geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[0].x+1;
                                geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[0].y;
                                geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-1].x+1;
                                geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-1].y;
                                geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }

                            if(key=='bottom_left'){
                                geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-1].x;
                                geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-1].y;
                                geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-2].x;
                                geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-2].y;
                                geometry.vertices[0].z = geometry.vertices[1].z = -object.bleed;
                            }
                        }else{
                            if(key=='top' ){
                                geometry.vertices[0].x = geometry.vertices[1].x = shapePoint[shapePoint.length-6].x;
                                geometry.vertices[0].y = geometry.vertices[1].y = shapePoint[shapePoint.length-6].y;
                                geometry.vertices[2].x = geometry.vertices[3].x =shapePoint[shapePoint.length-5].x;
                                geometry.vertices[2].y = geometry.vertices[3].y =shapePoint[shapePoint.length-5].y;
                                geometry.vertices[0].z = geometry.vertices[2].z = -object.bleed;
                            }

                            if(key=='top_right'){
                                geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-5].x;
                                geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-5].y;
                                geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-4].x;
                                geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-4].y;
                                geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }


                            if(key=='right'){
                                    geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-5].x;
                                    geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-5].y;
                                    geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-4].x;
                                    geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-4].y;
                                    geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }


                            if(key=='bottom'){
                                    geometry.vertices[0].x = geometry.vertices[1].x = shapePoint[shapePoint.length-3].x;
                                    geometry.vertices[0].y = geometry.vertices[1].y = shapePoint[shapePoint.length-3].y;
                                    geometry.vertices[2].x = geometry.vertices[3].x =shapePoint[shapePoint.length-2].x;
                                    geometry.vertices[2].y = geometry.vertices[3].y =shapePoint[shapePoint.length-2].y;
                                    geometry.vertices[1].z = geometry.vertices[3].z = -object.bleed;
                            }

                            if(key=='bottom_right'){
                                 geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-4].x;
                                 geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-4].y;
                                 geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-3].x;
                                 geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-3].y;
                                 geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }


                            if(key=='left' || key=='top_left'){
                                geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[0].x+1;
                                geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[0].y;
                                geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-1].x+1;
                                geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-1].y;
                                geometry.vertices[2].z = geometry.vertices[3].z = -object.bleed;
                            }

                            if(key=='bottom_left'){
                                geometry.vertices[0].x = geometry.vertices[2].x = shapePoint[shapePoint.length-1].x;
                                geometry.vertices[0].y = geometry.vertices[2].y = shapePoint[shapePoint.length-1].y;
                                geometry.vertices[1].x = geometry.vertices[3].x =shapePoint[shapePoint.length-2].x;
                                geometry.vertices[1].y = geometry.vertices[3].y =shapePoint[shapePoint.length-2].y;
                                geometry.vertices[0].z = geometry.vertices[1].z = -object.bleed;
                            }
                        }

                        var mesh = new THREE.Mesh(geometry, material);

                        group_obj.add(mesh);
                }
            });
            scene.add(group_obj);
        }

        if( typeof max == "object" ) {

            controls.target.x = max.x / 2;
            controls.target.y = (min.y + max.y) / 2;
            controls.target.z = 0;
        }
        controls.addEventListener('change', render);

        //
        container.addEventListener( 'resize', onWindowResize, false );
        animate();
        process3D = false;
    }
    function renderComplete(i, length, callBack)
    {
        if( i == length ) {
            capture_3d(callBack);
        }
    }
    function generateTexture() {

        var size = 512, color,darkcolor;

        // create canvas
        canvas = document.createElement( 'canvas' );
        canvas.width = size;
        canvas.height = size;

        // get context
        var context = canvas.getContext( '2d' );

        // draw gradient
        context.rect( 0, 0, size, size );
        var gradient = context.createLinearGradient( 0, 0, size, size );
        if(objEdgeColor.color!=undefined)
            color = objEdgeColor.color;
        else
            color = "#383838";

        if(color=='black'){
            darkcolor = color;
            color = '#555555';
        }else if(color=='white'){
            darkcolor = '#dddddd';
        }else if(color=="#4E0E0E"){
            darkcolor = color;
            color = '#733C3C';
        }else
            darkcolor = 'transparent';

        gradient.addColorStop(0, color); // light blue
        gradient.addColorStop(1, darkcolor); // dark blue
        context.fillStyle = gradient;
        context.fill();

        return canvas;

    }
    function onWindowResize() {
        camera.aspect = 1000 / 550;
        camera.updateProjectionMatrix();
        renderer.setSize( 1000, 550 );
        render();
    }
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
    }
    function render() {
        renderer.render( scene, camera );

    }
    function isWebGLSupported() {
      var cvs = document.createElement('canvas');
      var contextNames = ["webgl","experimental-webgl","moz-webgl","webkit-3d"];
      var ctx;
      if ( navigator.userAgent.indexOf("MSIE") >= 0 && navigator.userAgent.indexOf("MSIE 7.0") < 0) {
        try {
          ctx = WebGLHelper.CreateGLContext(cvs, 'canvas');
        } catch(e) {}
      } else {
        for ( var i = 0; i < contextNames.length; i++ ) {
          try {
            ctx = cvs.getContext(contextNames[i]);
            if ( ctx ) break;
          } catch(e){}
        }
      }
      if ( ctx ) return true;
      return false;
    }
    function capture_3d(callBack)
    {
        var name = '3d_' + (new Date()).getTime() + '.png';
        $.ajax({
            url : "{{ URL.'/capture3d' }}",
            type: "POST",
            data: {img: $("#preview_content canvas")[0].toDataURL(),name3d: name},
            success: function(imgLink){
                if( typeof callBack == "function" ) {
                    console.log(callBack);
                    callBack(imgLink);
                }
                $("#img-link").val(imgLink);
            }
        });
    }

    function fillScene() {

        var planeGeo = new THREE.PlaneBufferGeometry( 2000.1, 2000.1 );

        // MIRROR planes
        // groundMirror = new THREE.Mirror( renderer, camera, { clipBias: 0.003, textureWidth: WIDTH, textureHeight: HEIGHT, color: 0x777777 } );

        // var mirrorMesh = new THREE.Mesh( planeGeo, groundMirror.material );
        // mirrorMesh.add( groundMirror );
        // mirrorMesh.rotateX( - Math.PI / 2 );
        // scene.add( mirrorMesh );

        // leftMirror = new THREE.Mirror( renderer, camera, { clipBias: 0.003, textureWidth: WIDTH, textureHeight: HEIGHT, color:0x889999 } );
        // var leftMirrorMesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 300, 300 ), leftMirror.material );
        // leftMirrorMesh.add( leftMirror );
        // leftMirrorMesh.position.y = 35;
        // leftMirrorMesh.position.x = 80;
        // leftMirrorMesh.rotation.y =270*Math.PI/180;
        // scene.add( leftMirrorMesh );

        // rightMirror = new THREE.Mirror( renderer, camera, { clipBias: 0.053, textureWidth: WIDTH, textureHeight: HEIGHT, color:0x888888 } );
        // var rightMirrorMesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 300, 300 ), rightMirror.material );
        // rightMirrorMesh.add( rightMirror );
        // rightMirrorMesh.position.y = 45;
        // rightMirrorMesh.position.x = -98;
        // rightMirrorMesh.rotation.y =90*Math.PI/180;
        // scene.add( rightMirrorMesh );

       var wall = THREE.ImageUtils.loadTexture( "/assets/js/canvas3d/img/wall1.jpg",{},function(){
                render();
       });

       var floor = THREE.ImageUtils.loadTexture( "/assets/js/canvas3d/img/floor1.jpg",{},function(){
                render();
       });



        // walls
        var planeTop = new THREE.Mesh( planeGeo, new THREE.MeshPhongMaterial( { color: 0xffffff } ) );
        planeTop.position.y = 2000;
        planeTop.rotateX( Math.PI / 2 );
        scene.add( planeTop );


        var planeBack = new THREE.Mesh( planeGeo, new THREE.MeshPhongMaterial( {
            color: 0x777777,
            map: wall
        } ) );
        planeBack.position.z = -1000;
        planeBack.position.y = 1000;
        scene.add( planeBack );

        var planeBottom = new THREE.Mesh( planeGeo, new THREE.MeshPhongMaterial( { color: 0x886622,map:floor } ) );
        planeBottom.position.z = 0;
        planeBottom.position.y = 0;
        planeBottom.rotation.x =270*Math.PI/180;
        scene.add( planeBottom );

        var planeFront = new THREE.Mesh( planeGeo, new THREE.MeshPhongMaterial( { color: 0xffffff,
            map: wall } ) );
        planeFront.position.z = 1000;
        planeFront.position.y = 1000;
        planeFront.rotateY( Math.PI );
        scene.add( planeFront );

        var planeRight = new THREE.Mesh( planeGeo, new THREE.MeshPhongMaterial( { color: 0xffffff,
            map: wall} ) );
        planeRight.position.x = 1000;
        planeRight.position.y = 1000;
        planeRight.rotateY( - Math.PI / 2 );
        scene.add( planeRight );

        var planeLeft = new THREE.Mesh( planeGeo, new THREE.MeshPhongMaterial( { color: 0x777777,
            map: wall} ) );
        planeLeft.position.x = -1000;
        planeLeft.position.y = 1000;
        planeLeft.rotateY( Math.PI / 2 );
        scene.add( planeLeft );

         var axisHelper = new THREE.AxisHelper( 50 );
        scene.add( axisHelper );



        // lights
        var mainLight = new THREE.PointLight( 0xcccccc,1.4, 150 );
        mainLight.position.y = 1200;
        scene.add( mainLight );

        // var greenLight = new THREE.PointLight( 0x00ff00, 0.25, 1000 );
        // greenLight.position.set( 0, 0, 0 );
        // scene.add( greenLight );

        // var redLight = new THREE.PointLight( 0xff0000, 0.25, 1000 );
        // redLight.position.set( - 1550, 50, 0 );
        // scene.add( redLight );

        // var blueLight = new THREE.PointLight( 0x7f7fff, 0.25, 1000 );
        // blueLight.position.set( 0, 50, 1550 );
        // scene.add( blueLight );

    }
</script>