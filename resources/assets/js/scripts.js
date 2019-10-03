'use strict';

	;( function ( document, window, index )
	{
		var inputs = document.querySelectorAll( '.inputfile' );
		Array.prototype.forEach.call( inputs, function( input )
		{
			var label	 = input.nextElementSibling,
				labelVal = label.innerHTML;

			input.addEventListener( 'change', function( e )
			{
				var fileName = '';
				if( this.files && this.files.length > 1 )
					fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
				else
					fileName = e.target.value.split( '\\' ).pop();

				if( fileName )
					label.querySelector( 'span' ).innerHTML = fileName;
				else
					label.innerHTML = labelVal;
			});

			// Firefox bug fix
			input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
			input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
		});
	}( document, window, 0 ));

	if (window.location.href.indexOf("profile") > -1) {
		document.getElementById('avatarFile').addEventListener('change', readURL, true);
        function readURL(){
        var file = document.getElementById("avatarFile").files[0];
        var reader = new FileReader();
        reader.onloadend = function(){
            document.getElementById('avatar').style.background = "url(" + reader.result + ")";        
        }
        if(file){
            reader.readAsDataURL(file);
            }else{
            }
        }
	}

