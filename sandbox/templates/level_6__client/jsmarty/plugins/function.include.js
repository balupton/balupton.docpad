function jsmarty_function_include(H,C){if(!("file" in H)){C.trigger_error("fetch : parameter \"file\" cannot be empty","die");return ;}var k,q,r=JSmarty.Templatec;var I=C.get_resource_name(H.file);var d,w=new JSmarty.Classes.Item(I);if(r.isCompiled(w,C.force_compile)||r.newTemplate(w.load(C),C.get_compiler())){delete (H.file);q=C.$vars;C.$vars=JSmarty.Plugin.get("util.clone")(q);for(k in H){C.assign(k,H[k]);}d=r.call(I,C);C.$vars=q;}return d;}