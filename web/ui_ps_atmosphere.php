<?php
// insert header that forbids caching and carries doctype
// and html tag;
require('includes/noCacheHeader.inc');
?>
<META name="verify-v1" content="CwbLBcFt9+GqRTgaLZsENmPnSWNB5MStHHdYsB7U2nI=">
<title>Community Radiative Transfer Model (CRTM) - User Interface:Public Structures:Atmosphere</title>
<?php
// style links and the icon link
// pulls in .css files for regular and print display
require('includes/styleLinks.inc');
?>

<!-- if you need to include your own .css files
     put the links HERE, before the closing head tag. -->

<link href="crtm.css" type="text/css" rel="stylesheet">
<!-- DO NOT DELETE -->
<!--[if IE]>
  <link href="crtm.ie.css" type="text/css" rel="stylesheet">
<![endif]-->

</head>
<body>
<?php
// insert banner rows
require('includes/banner.inc');

?>
  <tr>
    <td id="navCell">
			<?php
			// insert navigation div
			require('includes/NavDiv.inc');
			?>
		</td>
		<td class="mainPanel"><a name="skipTarget"></a><?php require('includes/noScriptWarning.inc'); ?>
			<div class="padding">
				<!-- DO NOT DELETE OR ALTER CODE ABOVE THIS COMMENT -->
				<!-- EXCEPT for the contents of the <title></title> TAG!! -->
				<!-- You can start project specific content HERE -->
			
<h1><acronym title="Community Radiative Transfer Model">CRTM</acronym> User Interface: Public Structures: Atmosphere</h1>

<p>The <tt>Atmosphere</tt> data structure is used to contain information about the atmospheric state including both cloud and aerosol information. Some example declarations,
<pre>
  <strong>! Example declaration for a scalar structure</strong>
  TYPE(CRTM_Atmosphere_type) :: Atm
  
  <strong>! Example declaration for a single profile</strong>
  TYPE(CRTM_Atmosphere_type) :: Atm(1)
  
  <strong>! Example declaration for a fixed number of profiles</strong>
  INTEGER, PARAMETER :: N_PROFILES = 10
  TYPE(CRTM_Atmosphere_type) :: Atm(N_PROFILES)
  
  <strong>! Example declaration as an allocatable</strong>
  TYPE(CRTM_Atmosphere_type), ALLOCATABLE :: Atm(:)
</pre>
<p>For use with the CRTM functions - <tt>CRTM_Forward()</tt>, <tt>CRTM_Tangent_Linear()</tt>, <tt>CRTM_Adjoint()</tt>,
or <tt>CRTM_K_Matrix()</tt>) - the <tt>Atmosphere</tt> structure should <em>always</em> be declared as a rank-1 array with each
element corresponding to a different atmospheric profile.

<p>The <tt>Atmosphere</tt> structure allocation, assignment, and destruction routines have been overloaded
to accept either a scalar structure or a rank-1 structure array as arguments.


<h3><a name="atmdefine"></a><code>Atmosphere</code> Definition</h3>

<p>The <tt>Atmosphere</tt> structure definition is shown below.
<pre>
  TYPE :: CRTM_Atmosphere_type
    <strong>! Dimension values</strong>
    INTEGER :: Max_Layers   = 0  ! K dimension
    INTEGER :: n_Layers     = 0  ! Kuse dimension
    INTEGER :: n_Absorbers  = 0  ! J dimension
    INTEGER :: Max_Clouds   = 0  ! Nc dimension
    INTEGER :: n_Clouds     = 0  ! NcUse dimension
    INTEGER :: Max_Aerosols = 0  ! Na dimension
    INTEGER :: n_Aerosols   = 0  ! NaUse dimension
    <strong>! Climatology model associated with the profile</strong>
    INTEGER :: <a href="javascript:open_table_window('/crtm/tables/climatologymodel.html')">Climatology</a> = INVALID_MODEL
    <strong>! Absorber ID and units</strong>
    INTEGER, DIMENSION(:), POINTER :: <a href="javascript:open_table_window('/crtm/tables/absorberid.html')">Absorber_ID</a>    => NULL() ! J
    INTEGER, DIMENSION(:), POINTER :: <a href="javascript:open_table_window('/crtm/tables/absorberunits.html')">Absorber_Units</a> => NULL() ! J
    <strong>! Profile data</strong>
    REAL(fp), DIMENSION(:),   POINTER :: Level_Pressure    => NULL()  ! 0:K
    REAL(fp), DIMENSION(:),   POINTER :: Pressure          => NULL()  ! K
    REAL(fp), DIMENSION(:),   POINTER :: Temperature       => NULL()  ! K
    REAL(fp), DIMENSION(:,:), POINTER :: Absorber          => NULL()  ! K x J
    <strong>! Cloud and aerosol structures</strong>
    TYPE(CRTM_Cloud_type),   DIMENSION(:), POINTER :: Cloud   => NULL()  ! Nc
    TYPE(CRTM_Aerosol_type), DIMENSION(:), POINTER :: Aerosol => NULL()  ! Na
  END TYPE CRTM_Atmosphere_type
</pre>

<p>Because different profiles are likely to have different dimensions (e.g. different vertical layering, different
numbers of clouds, etc), to minimise repeated allocations and deallocations of an atmosphere structure to those
varying dimensions both maximum (e.g. <tt>Max_Layers</tt>, <tt>Max_Clouds</tt>, etc) and used (e.g. <tt>n_Layers</tt>,
<tt>n_Clouds</tt>) dimensions are provided in the structure. Thus, the structure can be allocated using some expected
maximum dimension values and the actual used dimensions are specified by setting the appropriate used dimension value. 
This is discussed in more detail in the <a href="#atmalloc">allocation section</a> below.

<p>The <tt>Atmosphere</tt> structure has <tt>Cloud</tt> and <tt>Aerosol</tt> structure components which are used to contain the
information about clouds and aerosols which is passed into the CRTM scattering optical properties procedures. The <tt>Cloud</tt>
structure definition is,
<pre>
  TYPE :: CRTM_Cloud_type
    <strong>! Dimension values</strong>
    INTEGER :: n_Layers = 0  ! K dimension.
    <strong>! Cloud type</strong>
    INTEGER :: <a href="javascript:open_table_window('/crtm/tables/cloudtype.html')">Type</a> = NO_CLOUD
    <strong>! Cloud state variables</strong>
    REAL(fp), DIMENSION(:), POINTER :: Effective_Radius   => NULL() ! K. Units are microns
    REAL(fp), DIMENSION(:), POINTER :: Effective_Variance => NULL() ! K.
    REAL(fp), DIMENSION(:), POINTER :: Water_Content      => NULL() ! K. Units are kg/m^2
  END TYPE CRTM_Cloud_type
</pre>
with a similar format for the <tt>Aerosol</tt> structure definition,
<pre>
  TYPE :: CRTM_Aerosol_type
    <strong>! Dimensions</strong>
    INTEGER :: n_Layers  = 0  ! K dimension
    <strong>! Aerosol type</strong>
    INTEGER :: <a href="javascript:open_table_window('/crtm/tables/aerosoltype.html')">Type</a> = NO_AEROSOL
    <strong>! Aerosol state variables</strong>
    REAL(fp), DIMENSION(:), POINTER :: Effective_Radius => NULL() ! K. Units are microns
    REAL(fp), DIMENSION(:), POINTER :: Concentration    => NULL() ! K. Units are kg/m^2  
  END TYPE CRTM_Aerosol_type
</pre>
<p>Note that each <tt>Cloud</tt> and <tt>Aerosol</tt> structure describe a <em>single</em> type
of cloud or aerosol. Multiple cloud and aerosol types associated with a single <tt>Atmosphere</tt>
structure are handled by the <tt>Cloud</tt> and <tt>Aerosol</tt> components being allocatable to
the required number of clouds/aerosol types.

<p>The allocation, assignment, and destruction of the child <tt>Cloud</tt> and <tt>Aerosol</tt> structures
are handled by the corresponding parent <tt>Atmosphere</tt> manipulation routines and will not be
covered separately here.


<h3><a name="atmalloc"></a>Allocation of <code>Atmosphere</code> Structures</h3>

Before populating the <tt>Atmosphere</tt> structure with atmospheric profile data, it must first be allocated.
The <tt>Atmosphere</tt> structure allocation calling sequence is,

<pre>
  Error_Status = CRTM_Allocate_Atmosphere( n_Layers               , &  ! Input
                                           n_Absorbers            , &  ! Input
                                           n_Clouds               , &  ! Input
                                           n_Aerosols             , &  ! Input
                                           Atmosphere             , &  ! Output
                                           RCS_Id     =RCS_Id     , &  ! Revision control
                                           Message_Log=Message_Log  )  ! Error messaging
</pre>

As mentioned previously, to minimise structure allocations and deallocations, one can allocate the
<code>Atmosphere</code> structure using maximum dimensions and then "re-use" the structure with different
dimensions as required. An example of this is shown below,

<pre>
  <strong>! Define some dimensions</strong>
  INTEGER, PARAMETER :: MAX_LAYERS = 100, MAX_CLOUDS = 10, MAX_AEROSOLS = 15
  INTEGER, PARAMETER :: N_ABSORBERS = 2<acronym title="Currently, only 2 absorbers allowed"><img src="/crtm/images/caution.gif" alt="" name="caution" width="20" height="17" align="top"></acronym>
		
  <strong>! Declare a single profile structure array</strong>
  TYPE(CRTM_Atmosphere_type) :: Atm(1)

  <strong>! Allocate the structure to maximum dimensions</strong>
  Error_Status = CRTM_Allocate_Atmosphere( MAX_LAYERS  , &
                                           N_ABSORBERS , &
                                           MAX_CLOUDS  , &
                                           MAX_AEROSOLS, &
                                           Atm           )

  <strong>! Define the structure dimensions to use</strong>
  Atm%n_Layers   = 64
  Atm%n_Clouds   = 2
  Atm%n_Aerosols = 3
</pre>

<p>Various combinations of the dimensionalities of the arguments in the <code>CRTM_Allocate_Atmosphere()</code> function
are allowed. These multiple interfaces are supplied purely for convenience depending on the input data; for example, different
profiles may have the same number of layers, but different numbers of clouds. The allowed input/output argument dimensioality
for the <code>CRTM_Allocate_Atmosphere()</code> function is shown below,

<div class="tablecontent2">
  <table cellspacing="0" cellpadding="0" border="0">
	 <caption>Allowable dimensionality combinations for the <tt>CRTM_Allocate_Atmosphere()</tt> function
           <br><tt>M</tt> = number of atmospheric profiles.</caption>
  <tbody>
    <tr align="center">
      <th>Input<br><tt>n_Layers</tt><br>dimension</th>
      <th>Input<br><tt>n_Absorbers</tt><br>dimension</th>
      <th>Input<br><tt>n_Clouds</tt><br>dimension</th>
      <th>Input<br><tt>n_Aerosols</tt><br>dimension</th>
      <th>Output<br><tt>Atmosphere</tt><br>dimension</th>
    </tr>
    <tr align="center"><td>Scalar    </td><td>Scalar</td><td>Scalar    </td><td>Scalar    </td><td>Scalar    </td></tr>
    <tr align="center"><td>Scalar    </td><td>Scalar</td><td>Scalar    </td><td>Scalar    </td><td><tt>M</tt></td></tr>
    <tr align="center"><td>Scalar    </td><td>Scalar</td><td>Scalar    </td><td><tt>M</tt></td><td><tt>M</tt></td></tr>
    <tr align="center"><td>Scalar    </td><td>Scalar</td><td><tt>M</tt></td><td>Scalar    </td><td><tt>M</tt></td></tr>
    <tr align="center"><td>Scalar    </td><td>Scalar</td><td><tt>M</tt></td><td><tt>M</tt></td><td><tt>M</tt></td></tr>
    <tr align="center"><td><tt>M</tt></td><td>Scalar</td><td>Scalar    </td><td>Scalar    </td><td><tt>M</tt></td></tr>
    <tr align="center"><td><tt>M</tt></td><td>Scalar</td><td>Scalar    </td><td><tt>M</tt></td><td><tt>M</tt></td></tr>
    <tr align="center"><td><tt>M</tt></td><td>Scalar</td><td><tt>M</tt></td><td>Scalar    </td><td><tt>M</tt></td></tr>
    <tr align="center"><td><tt>M</tt></td><td>Scalar</td><td><tt>M</tt></td><td><tt>M</tt></td><td><tt>M</tt></td></tr>
  </tbody>
</table>
</div>


<h3><a name="atmassign"></a>Assignment (copying) of <code>Atmosphere</code> Structures</h3>

<p>A specific assign function is provided to ensure that when an <tt>Atmosphere</tt> structure needs to be
copied, the pointer components are explicitly allocated and copied (deep copy). This means that the
original structure can be destroyed without affecting the copied structure.

<p>The <tt>Atmosphere</tt> structure assign calling sequence is,
<pre>
  Error_Status = CRTM_Assign_Atmosphere( Atmosphere_In          , &  ! Input
                                         Atmosphere_Out         , &  ! Output
                                         RCS_Id     =RCS_Id     , &  ! Revision control
                                         Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>which is semantically equivalent to
<pre>
  Atmosphere_Out = Atmosphere_In
</pre>

<p>Either scalar or rank-1 <tt>Atmosphere</tt> structures can be assigned. In the latter case, the dimensions
of the input and output <tt>Atmosphere</tt> structure arrays must be the same.


<h3><a name="atmdestroy"></a>Destruction (deallocation) of <code>Atmosphere</code> Structures</h3>
<p>The <tt>Atmosphere</tt> structure destruction calling sequence is,
<pre>
  Error_Status = CRTM_Destroy_Atmosphere( Atmosphere             , &  ! In/Output
                                          RCS_Id     =RCS_Id     , &  ! Revision control
                                          Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>which deallocates all the pointer components and clears the scalar components. Either scalar or rank-1
<tt>Atmosphere</tt> structures can be destroyed.

				<!-- END your project-specific content HERE -->
				<!-- DO NOT DELETE OR ALTER BELOW THIS COMMENT! -->
			</div>
		</td>
	</tr>
<?php
// insert footer & javascript include for menuController
require('includes/footer.inc');
?>
</table>
</body>
</html>
