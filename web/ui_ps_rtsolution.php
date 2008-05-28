<?php
// insert header that forbids caching and carries doctype
// and html tag;
require('includes/noCacheHeader.inc');
?>
<META name="verify-v1" content="CwbLBcFt9+GqRTgaLZsENmPnSWNB5MStHHdYsB7U2nI=">
<title>Community Radiative Transfer Model (CRTM) - User Interface:Public Structures:RTSolution</title>
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
			
<h1><acronym title="Community Radiative Transfer Model">CRTM</acronym> User Interface: Public Structures: RTSolution</h1>

<p>The <tt>RTSolution</tt> structure contains the CRTM model results for a single sensor channel. Thus the
<tt>RTSolution</tt> structure should be dimensioned accordingly for the number of channels and the number
of profiles. Some example declarations,
<pre>
  <strong>! Example declaration for a known number of channels and profiles</strong>
  INTEGER, PARAMETER :: N_CHANNELS = 100
  INTEGER, PARAMETER :: N_PROFILES = 10
  TYPE(CRTM_RTSolution_type) :: RTSolution(N_CHANNELS,N_PROFILES)
  
  <strong>! Example declaration as an allocatable</strong>
  TYPE(CRTM_RTSolution_type), ALLOCATABLE :: RTSolution(:,:)
</pre>
<p>For use with the CRTM functions <a href="/crtm/user_interface/components/forward.shtml"><tt>CRTM_Forward()</tt></a>,
<a href="/crtm/user_interface/components/tangent_linear.shtml"><tt>CRTM_Tangent_Linear()</tt></a>,
<a href="/crtm/user_interface/components/adjoint.shtml"><tt>CRTM_Adjoint()</tt></a>, or 
<a href="/crtm/user_interface/components/k_matrix.shtml"><tt>CRTM_K_Matrix()</tt></a>, the <tt>RTSolution</tt> structure
<em>must</em> be declared as a rank-2 array where the first dimension must correspond to the channel and the second
dimension to the atmospheric profile.


<h3><a name="rtsdefine"></a><code>RTSolution</code> Definition</h3>

<p>The <tt>RTSolution</tt> structure definition is shown below,

<pre>
  TYPE :: CRTM_RTSolution_type
    <strong>! Dimensions</strong>
    INTEGER :: n_Layers = 0  ! K
    <strong>! Forward radiative transfer intermediate results.
    !    These components are not defined when the structure
    !    is used as a TL, AD, or K variable</strong>
    REAL(fp) :: Surface_Emissivity      = FP_DEFAULT
    REAL(fp) :: Up_Radiance             = FP_DEFAULT
    REAL(fp) :: Down_Radiance           = FP_DEFAULT
    REAL(fp) :: Down_Solar_Radiance     = FP_DEFAULT
    REAL(fp) :: Surface_Planck_Radiance = FP_DEFAULT
    REAL(fp), DIMENSION(:), POINTER :: Layer_Optical_Depth => NULL()  ! K
    <strong>! Internal variables.
    !    Users should not use or rely on these as they will
    !    be eventually removed from this structure.</strong>
    INTEGER :: n_Full_Streams  = IP_DEFAULT
    LOGICAL :: Scattering_Flag = LP_DEFAULT
    INTEGER :: n_Stokes        = IP_DEFAULT
    <strong>! Radiative transfer results for a single channel/node</strong>
    REAL(fp) :: Radiance               = FP_DEFAULT
    REAL(fp) :: Brightness_Temperature = FP_DEFAULT
  END TYPE CRTM_RTSolution_type
</pre>
where the <em>XX</em><tt>_DEFAULT</tt> parameters are initialisation values for the various datatypes.

<p>The large majority of users will be interested only in the <tt>Radiance</tt> and <tt>Brightness_Temperature</tt>
components of the <tt>RTSolution</tt> structure.

<p>The section in the structure definition for the forward model intermediate results contains
variables required for certain applications - the emissivity and radiance intermediate values are used in surface
emissivity retrieval algorithms, and the layer optical depths are used in the GDAS radiance bias correction.

<p>The so-called "internal variables" mentioned above are simply design shortcuts for information passing - eventually
they will be removed from this structure so users should not rely on them being present.  


<h3><a name="rtsalloc"></a>Allocation of <code>RTSolution</code> structures</h3>

<p>The calling sequence for the <tt>RTSolution</tt> structure allocation function is,

<pre>
  Error_Status = CRTM_Allocate_RTSolution( n_Layers               , &  ! Input
                                           RTSolution             , &  ! Output
                                           RCS_Id     =RCS_Id     , &  ! Revision control
                                           Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>The <tt>RTSolution</tt> allocation function has been overloaded such that the <tt>RTSolution</tt> argument can be
scalar, rank-1, or rank-2. The allowed input/output argument dimensioality for the <code>CRTM_Allocate_RTSolution()</code>
function is shown below,

<div class="tablecontent2">
  <table cellspacing="0" cellpadding="0" border="0">
	 <caption>Allowable dimensionality combinations for the <tt>CRTM_Allocate_RTSolution()</tt> function
           <br><tt>L</tt> = total number of sensor channels.
           <br><tt>M</tt> = number of atmospheric profiles.
  </caption>
  <tbody>
    <tr align="center">
      <th>Input<br><tt>n_Layers</tt><br>dimension</th>
      <th>Output<br><tt>RTSolution</tt><br>dimension</th>
    </tr>
    <tr align="center"><td>Scalar    </td><td>Scalar    </td></tr>
    <tr align="center"><td>Scalar    </td><td>Rank-1: <tt>L</tt> or <tt>M</tt></td></tr>
    <tr align="center"><td>Scalar    </td><td>Rank-2: <tt>L x M</tt></td></tr>
  </tbody>
</table>
</div>

<p>As stated above, the rank-2 dimensionality of the <tt>RTSolution</tt> structure array is what is required
for all the CRTM functions.

<p>Depending on how the CRTM is used, the <tt>RTSolution</tt> structure array itself may need to be allocated. In general, 
the number of sensor channels to be processed is either a predetermined quantity, or it is not known until run time. In the latter
case the <tt>RTSolution</tt> structure array must be allocated prior to calling the <code>CRTM_Allocate_RTSolution()</code> function, in the former it is simply declared to be the required size in the calling procedure. Both of these situations are shown in the examples
below.

<p><u>Example 1: The number of channels is known</u>

<p>Here we assume the same sensors (in this case from NOAA-18) and, thus, the same number of channels,
will <em>always</em> be processed,

<pre>
  <strong>! Example parameter definitions</strong>
  INTEGER, PARAMETER :: N_SENSORS  = 3   ! # of sensors to process,  N dimension
  INTEGER, PARAMETER :: N_LAYERS   = 60  ! # of atmospheric layers,  K dimension
  INTEGER, PARAMETER :: N_CHANNELS = 39  ! # of sensor channels,     L dimension
  INTEGER, PARAMETER :: N_PROFILES = 50  ! # of profiles to process, M dimension
  CHARACTER(STRLEN), PARAMETER :: &      ! Note: STRLEN is defined via CRTM_Module
    SENSORID(N_SENSORS) = (/'hirs4_n18', &  ! 19 channels
                            'amsua_n18', &  ! 15 channels
                            'mhs_n18  ' /)  !  5 channels

  <strong>! Type declarations</strong>
  TYPE(CRTM_ChannelInfo_type) :: ChInfo(N_SENSORS)
  TYPE(CRTM_RTSolution_type) :: RTSolution(N_CHANNELS,N_PROFILES)
		
  <strong>! Allocate the RTSolution components for each array element</strong>
  Error_Status = CRTM_Allocate_RTSolution( N_LAYERS, RTSolution )
  IF ( Error_Status /= SUCCESS ) THEN
    ! Handle error
    ....
  END IF
</pre>

<p><u>Example 2: The number of channels is NOT known</u>

<p>Here we assume a known numbers of sensors, but not a known number of channels; that is, a different set of sensors
may be processed each time. In this case, we need to determine the number of channels the CRTM "knows" about after the
CRTM has been <a href="/crtm/user_interface/initialize.shtml">initialised</a> by getting the value from the
<a href="/crtm/user_interface/public_structures/channelinfo.shtml">ChannelInfo</a> structure,

<pre>
  <strong>! Example parameter definitions</strong>
  INTEGER :: N_SENSORS  = 4   ! # of sensors to process,  N dimension
  INTEGER :: N_LAYERS   = 60  ! # of atmospheric layers,  K dimension
  INTEGER :: N_PROFILES = 50  ! # of profiles to process, M dimension
		
  <strong>! Type declarations</strong>
  CHARACTER(STRLEN) :: SensorID(N_SENSORS)
  INTEGER :: n_Channels       ! # of channels to process, L dimension
  TYPE(CRTM_ChannelInfo_type) :: ChInfo(N_SENSORS)
  TYPE(CRTM_RTSolution_type), ALLOCATABLE :: RTSolution(:,:) ! L x M allocatable
		
  <strong>! Ask user to specify the sensor IDs for any four sensors</strong>
  WRITE(*,'(/5x,"Specify ",i0," sensors to process,")') N_SENSORS
  DO i = 1, N_SENSORS
    WRITE(*,'(7x,"Sensor #",i0," : ")',ADVANCE='NO') i
    READ(*,'(a)') SensorID(i)
  END DO

  <strong>! Initialise the CRTM for the four arbitrary sensors</strong>
  Error_Status = CRTM_Init(ChInfo,SensorID=SensorID)
  IF ( Error_Status /= SUCCESS ) THEN
    ! Handle error
    ....
  END IF

  <strong>! Determine the total number of channels for
  ! which the CRTM was initialised</strong>
  n_Channels = SUM(ChInfo%n_Channels)
		
  <strong>! STEP 1: Allocate the RTSolution structure array</strong>
  ALLOCATE( RTSolution(n_Channels, N_PROFILES), STAT=Allocate_Status)
  IF ( Allocate_Status /= 0 ) THEN
    ! Handle error
    ....
  END IF

  <strong>! STEP 2: Allocate the RTSolution components for each array element</strong>
  Error_Status = CRTM_Allocate_RTSolution( N_LAYERS, RTSolution )
  IF ( Error_Status /= SUCCESS ) THEN
    ! Handle error
    ....
  END IF
</pre>


<h3><a name="rtsassign"></a>Assignment (copying) of <code>RTSolution</code> Structures</h3>

<p>As with all the other structures that contain allocatable pointer components, a specific
assign function is provided to ensure that when a <tt>RTSolution</tt> structure needs to be
copied, the pointer components are explicitly allocated and copied (deep copy). This means that the
original structure can be destroyed without affecting the copied structure.

<p>The <tt>RTSolution</tt> structure assign calling sequence is,

<pre>
  Error_Status = CRTM_Assign_RTSolution( RTSolution_in          , &  ! Input
                                         RTSolution_out         , &  ! Output
                                         RCS_Id     =RCS_Id     , &  ! Revision control
                                         Message_Log=Message_Log  )  ! Error messaging
</pre>

<p>which is semantically equivalent to

<pre>
  RTSolution_Out = RTSolution_In
</pre>


<h3><a name="rtsdestroy"></a>Destruction (deallocation) of <code>RTSolution</code> Structures</h3>

<p>The <tt>RTSolution</tt> structure destruction calling sequence is,

<pre>
  Error_Status = CRTM_Destroy_RTSolution( RTSolution             , &  ! Output
                                          RCS_Id     =RCS_Id     , &  ! Revision control
                                          Message_Log=Message_Log  )  ! Error messaging
</pre>
<p>which deallocates all the pointer components and clears the scalar components.

<p>Note that the destruction function deallocates the <tt>RTSolution</tt> components, it does not
deallocate the actual structure array (assuming it was allocated.) To do that, a regular Fortran95
deallocate statement is used,
<pre>
  <strong>! Dellocate the RTSolution structure array</strong>
  DEALLOCATE( RTSolution, STAT=Allocate_Status)
  IF ( Allocate_Status /= 0 ) THEN
    ! Handle error
    ....
  END IF
</pre>

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
