var jsmarty_function_cycle_cycle_vars={};function jsmarty_function_cycle(w,k){var q=jsmarty_function_cycle_cycle_vars;var I,r,d,f,C,H;var i,j=w.values,F=w.assign;I=w.name||"default";d=(w.print==void (0))?true:w.print;r=(w.reset==void (0))?false:w.reset;f=(w.advance==void (0))?true:w.advance;if(!(C=q[I])){C=q[I]={index:0,values:""};}if(!j){if(!C.values){k.trigger_error("cycle: missing 'values' parameter");return "";}}else{if(!C.values&&C.values!=j){C.index=0;}C.values=j;}C.delimiter=(w.delimiter)?w.delimiter:",";if(C.values&&C.values instanceof Array){H=C.values;}else{H=C.values.split(C.delimiter);}if(!C.index||r){C.index=0;}if(F){d=false;k.assign(F,H[C.index]);}if(d){i=H[C.index];}else{i="";}if(f){if(C.index>=H.length-1){C.index=0;}else{C.index++;}}return i;}