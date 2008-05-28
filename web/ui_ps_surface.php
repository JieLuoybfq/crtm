<?php
// insert header that forbids caching and carries doctype
// and html tag;
require('includes/noCacheHeader.inc');
?>
<META name="verify-v1" content="CwbLBcFt9+GqRTgaLZsENmPnSWNB5MStHHdYsB7U2nI=">
<title>Community Radiative Transfer Model (CRTM) - User Interface:Public Structures:Surface</title>
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
			
<h1><acronym title="Community Radiative Transfer Model">CRTM</acronym> User Interface: Public Structures: Surface</h1>

<p>Similarly to the <tt>Atmosphere</tt> structure, the <tt>Surface</tt> data structure is used to contain information
about the surface state. Some example declarations,

<pre>
  <strong>! Example declaration for a scalar structure</strong>
  TYPE(CRTM_Surface_type) :: Sfc
		
  <strong>! Example declaration for a single location</strong>
  TYPE(CRTM_Surface_type) :: Sfc(1)

  <strong>! Example declaration for a fixed number of locations</strong>
  INTEGER, PARAMETER :: N_PROFILES = 10
  TYPE(CRTM_Surface_type) :: Sfc(N_PROFILES)

  <strong>! Example declaration as an allocatable</strong>
  TYPE(CRTM_Surface_type), ALLOCATABLE :: Sfc(:)
</pre>
<p>For use with the CRTM functions - <tt>CRTM_Forward()</tt>, <tt>CRTM_Tangent_Linear()</tt>, <tt>CRTM_Adjoint()</tt>,
or <tt>CRTM_K_Matrix()</tt>) - the <tt>Surface</tt> structure should <em>always</em> be declared as a rank-1 array with each
element corresponding to a different surface location/atmospheric profile.

<p>The <tt>Surface</tt> structure allocation, assignment, and destruction routines have been overloaded
to accept either a scalar structure or a rank-1 structure array as arguments.

<h3> <code>Surface</code> Definition</h3>
<!-- ================================= -->
<p>The <tt>Surface</tt> structure definition is shown below.

<pre>
  TYPE :: CRTM_Surface_type
    INTEGER :: n_Allocates = 0
    <strong>! Dimension values</strong>
    INTEGER :: Max_Sensors  = 0  ! N dimension
    INTEGER :: n_Sensors    = 0  ! Nuse dimension
    <strong>! Gross type of surface determined by coverage</strong>
    REAL(fp) :: Land_Coverage  = ZERO
    REAL(fp) :: Water_Coverage = ZERO
    REAL(fp) :: Snow_Coverage  = ZERO
    REAL(fp) :: Ice_Coverage   = ZERO
    <strong>! Surface type independent data</strong>
    REAL(fp) :: Wind_Speed = DEFAULT_WIND_SPEED
    <strong>! Land surface type data</strong>
    INTEGER  :: <a href="javascript:open_table_window('/crtm/tables/landtypes.html')">Land_Type</a>             = DEFAULT_LAND_TYPE
    REAL(fp) :: Land_Temperature      = DEFAULT_LAND_TEMPERATURE
    REAL(fp) :: Soil_Moisture_Content = DEFAULT_SOIL_MOISTURE_CONTENT
    REAL(fp) :: Canopy_Water_Content  = DEFAULT_CANOPY_WATER_CONTENT
    REAL(fp) :: Vegetation_Fraction   = DEFAULT_VEGETATION_FRACTION
    REAL(fp) :: Soil_Temperature      = DEFAULT_SOIL_TEMPERATURE
    <strong>! Water type data</strong>
    INTEGER  :: <a href="javascript:open_table_window('/crtm/tables/watertypes.html')">Water_Type</a>        = DEFAULT_WATER_TYPE
    REAL(fp) :: Water_Temperature = DEFAULT_WATER_TEMPERATURE
    REAL(fp) :: Wind_Direction    = DEFAULT_WIND_DIRECTION
    REAL(fp) :: Salinity          = DEFAULT_SALINITY
    <strong>! Snow surface type data</strong>
    INTEGER  :: <a href="javascript:open_table_window('/crtm/tables/snowtypes.html')">Snow_Type</a>        = DEFAULT_SNOW_TYPE
    REAL(fp) :: Snow_Temperature = DEFAULT_SNOW_TEMPERATURE
    REAL(fp) :: Snow_Depth       = DEFAULT_SNOW_DEPTH
    REAL(fp) :: Snow_Density     = DEFAULT_SNOW_DENSITY
    REAL(fp) :: Snow_Grain_Size  = DEFAULT_SNOW_GRAIN_SIZE
    <strong>! Ice surface type data</strong>
    INTEGER  :: <a href="javascript:open_table_window('/crtm/tables/icetypes.html')">Ice_Type</a>        = DEFAULT_ICE_TYPE
    REAL(fp) :: Ice_Temperature = DEFAULT_ICE_TEMPERATURE
    REAL(fp) :: Ice_Thickness   = DEFAULT_ICE_THICKNESS
    REAL(fp) :: Ice_Density     = DEFAULT_ICE_DENSITY
    REAL(fp) :: Ice_Roughness   = DEFAULT_ICE_ROUGHNESS
    <strong>! SensorData containing channel brightness temperatures</strong>
    TYPE(CRTM_SensorData_type) :: SensorData  ! N
  END TYPE CRTM_Surface_type
</pre>

<p>Combinations of all the valid surfaces are supported (even if unlikely to occur in practice.)
The ratio of the different surfaces for a mixed surface type is specified by the various
coverage components. Note that the total coverage (the sum of the land, water, snow, and ice
coverage fractions) should always equal 1.0.

<p>An additional structure, <tt>SensorData</tt>, is used to hold satellite sensor data in the
<tt>Surface</tt> structure for those algorithms that may use the data (e.g. some surface emissivity codes)
to improve their results. The <tt>CRTM_SensorData_type</tt> structure definition is,

<pre>
  TYPE :: CRTM_SensorData_type
    <strong>! Dimension values</strong>
    INTEGER :: n_Channels = 0  ! L dimension
    <strong>! The sensor ID and channels</strong>
    INTEGER :: StrLen = STRLEN
    CHARACTER(STRLEN), DIMENSION(:), POINTER :: <a href="javascript:open_table_window('/crtm/tables/sensorid.html')">SensorID</a>         => NULL()  ! L
    INTEGER,           DIMENSION(:), POINTER :: <a href="javascript:open_table_window('/crtm/tables/sensorid.html')">WMO_Satellite_ID</a> => NULL()  ! L
    INTEGER,           DIMENSION(:), POINTER :: <a href="javascript:open_table_window('/crtm/tables/sensorid.html')">WMO_Sensor_ID</a>    => NULL()  ! L
    INTEGER,           DIMENSION(:), POINTER :: Sensor_Channel   => NULL()  ! L
    <strong>! The sensor brightness temperatures</strong>
    REAL(fp), DIMENSION(:), POINTER :: Tb => NULL()  ! L
  END TYPE CRTM_SensorData_type
</pre>

<p>where the data from various instruments (e.g. AMSU-A, AMSU-B, SSM/I, etc) are combined into
a single array, <tt>Tb</tt>, and the associated instrument ID and channel arrays are used to 
identify the data from which channels and what instrument are present. The sensor and satellite
ID values are available in the <tt><a href="javascript:open_table_window('/crtm/tables/sensorid.html')">ChannelInfo</a></tt>
structure after model initialization.

<p>Currently, the only dimension value in the <tt>Surface</tt> structure is contained within the <tt>SensorData</tt>
component. If no sensor data is required or available, the <tt>Surface</tt> structure contains only scalar
components.


<h3><a name="sfcalloc"></a>Allocation of <code>Surface</code> Structures</h3>
<p>The <tt>Surface</tt> structure is slightly peculiar due to some of the algorithms used in the CRTM.
As mentioned above, some of the surface emissivity algorithms require satellite observations
to produce accurate results. Rather than pass this data separately, it was decided to incorporate
this information into the <tt>Surface</tt> structure via the <tt>SensorData</tt> structure. Users need only
allocate and use the <tt>SensorData</tt> component if it is required, or if the data itself is
available.

<p>Because the <tt>SensorData</tt> component of the <tt>Surface</tt> structure contains the only non-scalar
data elements, the <tt>Surface</tt> structure allocation is effectively a wrapper for the
<tt>CRTM_Allocate_SensorData</tt> function, but overloaded to provide similar functionality for
allocating scalar and rank-1 arrays of <tt>Surface</tt> structures. The <tt>Surface</tt> structure allocation calling
sequence is,

<pre>
  Error_Status = CRTM_Allocate_Surface( n_Channels             , &  ! Input
                                        Surface                , &  ! Output
                                        RCS_Id     =RCS_Id     , &  ! Revision control
                                        Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>If no satellite data is needed for surface algorithms, or if none is available, then a user can
either a) simply not call the <tt>Surface</tt> allocation function, or b) call the function, but with the
<tt>n_Channels</tt> argument set to 0.

<h3><a name="sfcassign"></a>Assignment (copying) of <code>Surface</code> Structures</h3>
<p>A specific assign function is provided to ensure that when an <tt>Surface</tt> structure needs to be
copied, the pointer components are explicitly allocated and copied (deep copy). This means that the
original structure can be destroyed without affecting the copied structure.

<p>The <tt>Surface</tt> structure assign calling sequence is,
<pre>
  Error_Status = CRTM_Assign_Surface( Surface_In             , &  ! Input
                                      Surface_Out            , &  ! Output
                                      RCS_Id     =RCS_Id     , &  ! Revision control
                                      Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>which is semantically equivalent to
<pre>
  Surface_Out = Surface_In
</pre>

<p>Either scalar or rank-1 <tt>Surface</tt> structures can be assigned. In the latter case, the dimensions
of the input and output <tt>Surface</tt> structure arrays must be the same.


<h3><a name="sfcdestroy"></a>Destruction (deallocation) of <code>Surface</code> Structures</h3>
<p>The <tt>Surface</tt> structure destruction calling sequence is,
<pre>
  Error_Status = CRTM_Destroy_Surface( Surface                , &  ! In/Output
                                       RCS_Id     =RCS_Id     , &  ! Revision control
                                       Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>which deallocates all the pointer components and clears the scalar components. Either scalar or rank-1
<tt>Surface</tt> structures can be destroyed.

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
