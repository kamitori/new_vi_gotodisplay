// filter = group.filter()



// //bright and constrast
// var bright = new SVG.ComponentTransferEffect;
// bright.attr({
//     id:"brightness"
// });
// (['r', 'g', 'b']).forEach(function(c) {
//             feFunc = new SVG['Func' + c.toUpperCase()]().attr({
//                 type:"linear",
//                 slope:($("#val-contrast").val()/100)+($("#val-bright").val()/100+1),
//                 intercept:(0.5*$("#val-contrast").val()/100)
//             })
//             bright.node.appendChild(feFunc.node)
//         });
// filter.add(bright)


// //hue
// hue = new SVG.ColorMatrixEffect;
// hue.attr({
//     type:"hueRotate",
//     values:$("#val-hue").val(),
//     id:"hue"
// })
// filter.add(hue);


// //saturate
// saturate = new SVG.ColorMatrixEffect;
// saturate.attr({
//     type:"saturate",
//     values:$("#val-saturate").val(),
//     id:"saturate"
// })
// filter.add(saturate);


// //gamma
// var gamma = new SVG.ComponentTransferEffect;
// gamma.attr({
//     id:"gamma"
// });
// (['r', 'g', 'b']).forEach(function(c) {
//             feFunc = new SVG['Func' + c.toUpperCase()]().attr({
//                 type:"gamma",
//                 amplitude: (($("#val-gamma").val()*0.9+0.1)*0.99+0.01 + (10*Math.pow(10,-$("#val-gamma").val())))/2,
//                 exponent: (($("#val-gamma").val()*0.9+0.1)*0.99+0.01)*0.999+0.001,
//             })
//             gamma.node.appendChild(feFunc.node)
//         });
// filter.add(gamma)

// //blur
// var blur = new SVG.GaussianBlurEffect;
// blur.attr({
//     stdDeviation:$("#val-blur").val(),
//     id:"blur"
// });
// filter.add(blur)

// //sharpen
// var sharpen = new SVG.ConvolveMatrixEffect;
// x = $("#val-sharpen").val();
// sharpen.attr({
//     // Matrix sharpen 5x5
//     // order:5,
//     // kernelMatrix:
//     //            (-x)+'                          '+(-x)+'                             '+(-x)+'                            '+(-x)+'                            '+(-x)+'        '+

//     //           (-x)+'                          '+(2*x)+'                          '+(2*x)+'                          '+(2*x)+'                          '+(-x)+'        '+

//     //           (-x)+'                          '+(2*x)+'                          '+(8*x+1)+'                          '+(2*x)+'                          '+(-x)+'        '+

//     //           (-x)+'                          '+(2*x)+'                          '+(2*x)+'                          '+(2*x)+'                          '+(-x)+'        '+

//     //           (-x)+'                          '+(-x)+'                             '+(-x)+'                            '+(-x)+'                             '+(-x),


//     // Matrix sharpen 3x3
//     order:3,
//     kernelMatrix:
//                0+'                              '+(-x)+'                              '+0+'                            '+

//               (-x)+'                            '+(4*x+1)+'                        '+(-x)+'                         '+

//               0+'                               '+(-x)+'                               '+0+'                            ',

//     id:"sharpen"
// });
// filter.add(sharpen)

// /*if(objImg.filter=='grayscale'){
//     grayscale = new SVG.ColorMatrixEffect;
//     grayscale.attr({
//              type:"saturate",
//               values:0,
//               id:"grayscale"
//         })
//     filter.add(grayscale);
// }else if(objImg.filter=='sepia'){
//     sepia = new SVG.ColorMatrixEffect;
//     sepia.attr({
//               id:"sepia",
//                 type:'matrix',
//                 values:'0.543 0.669 0.119 0 0 0.249 0.626 0.13 0 0 0.172 0.334 0.2 0 0 0 0 0 1 0',
//         })
//     filter.add(sepia);
// }*/